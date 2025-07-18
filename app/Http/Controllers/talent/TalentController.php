<?php

namespace App\Http\Controllers\talent;

use App\Http\Controllers\Controller;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TalentController extends Controller
{
    public function index()
    {
        $talent = auth()->user()->load([
            'talent',
            'talent.skills',
            'talent.experiences',
            'talent.projects',
            'talent.achievements',
            'talent.interests',
            'talent.educationHistories'
        ]);
        return response()->json([
            'status' => 'success',
            'data' => [
                'talent' => $talent,
            ]
        ], Response::HTTP_OK);
    }

    public function update(Request $request)
    {
        $data = Validator::make($request->all(), [
            'current_education' => 'sometimes|string|max:255',
            'goal_career' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|max:1000',
            'expected_salary' => 'sometimes|numeric|min:0',
            'date_of_birth' => 'sometimes|date|before:today',
        ]);
        if ($data->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $data->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $talent = auth()->user()->talent;

        $talent->update($data->validated());
        return response()->json([
            'status' => 'success',
            'message' => 'Talent profile updated successfully',
        ], Response::HTTP_OK);
    }

    public function userUpdate()
    {
        $data = Validator::make(request()->all(), [
            'full_name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255|unique:users,email,' . auth()->id(),
            'phone' => 'sometimes|required|string|max:20',
            'username' => 'sometimes|required|string|max:50|unique:users,username,' . auth()->id(),
            'profile_picture' => 'sometimes|required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'password' => 'sometimes|required|string'
        ]);
        if ($data->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $data->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $validatedData = $data->validated();

        $user = auth()->user();

        $user->update(collect($validatedData)->except(['password', 'profile_picture'])->toArray());

        if (isset($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
            $user->save();
        }
        if (request()->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $path = request()->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $path;
            $user->save();
        }
        return response()->json([
            'status' => 'success',
            'message' => 'User profile updated successfully',
        ], Response::HTTP_OK);
    }

    public function destroy()
    {
        $user = auth()->user();
        if ($user) {
            $user->talent->delete();
            $user->tokens()->delete();
            $user->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Talent profile deleted successfully'
            ], Response::HTTP_OK);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Talent profile not found'
        ], Response::HTTP_NOT_FOUND);
    }

    public function aiGenerateProfile()
    {
        $user = auth()->user()->load([
            'talent',
            'talent.skills',
            'talent.experiences',
            'talent.projects',
            'talent.achievements',
            'talent.interests',
            'talent.educationHistories'
        ]);

        if (!$user->talent) {
            return response()->json([
                'status' => 'error',
                'message' => 'Talent profile not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $promptData = [
            'full_name' => $user->full_name,
            'email' => $user->email,
            'current_education' => $user->talent->current_education,
            'skills' => $user->talent->skills->pluck('name')->toArray(),
            'experiences' => $user->talent->experiences->pluck('description')->toArray(),
            'projects' => $user->talent->projects->toArray(),
            'achievements' => $user->talent->achievements->toArray(),
            'interests' => $user->talent->interests->pluck('name')->toArray(),
            'education_history' => $user->talent->educationHistories->map(function ($education) {
                return [
                    'institution_name' => $education->institution_name,
                    'field_of_study' => $education->field_of_study,
                    'start_year' => $education->start_year,
                    'end_year' => $education->end_year,
                ];
            })->toArray(),
        ];

        $prompt = $this->craftGeminiPrompt($promptData);

        $geminiApiKey = env('GEMINI_API_KEY');
        $geminiApiUrl = env('GEMINI_API_URL');

        $client = new Client();

        try {
            $response = $client->post($geminiApiUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-goog-api-key' => $geminiApiKey,
                ],
                'json' => [
                    'contents' => [
                        [
                            "parts" => [
                                [
                                    'text' => $prompt,
                                ]
                            ]
                        ]
                    ]
                ],
                'timeout' => 30,
            ]);

            $statusCode = $response->getStatusCode();
            $responseData  = json_decode($response->getBody()->getContents(), true);

            if ($statusCode === 200 && isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
                $geminiOutput = $responseData['candidates'][0]['content']['parts'][0]['text'];

                $cleanedOutput = trim($geminiOutput, " \n`");
                if (str_starts_with($cleanedOutput, 'json')) {
                    $cleanedOutput = substr($cleanedOutput, 4);
                    $cleanedOutput = trim($cleanedOutput, " \n`");
                }

                $suggestions = json_decode($cleanedOutput, true); //final output

                if (json_last_error() === JSON_ERROR_NONE && isset($suggestions['profiles']) && is_array($suggestions['profiles']) && count($suggestions['profiles']) > 0) {
                    $suggestions = $suggestions['profiles'][0];
                    $aiProfileData = [
                        'goal_career' => $suggestions['goal_career'] ?? null,
                        'description' => $suggestions['description'] ?? null,
                        'expected_salary' => $suggestions['expected_salary'] ?? null,
                        'job_opportunity' => $suggestions['job_opportunity'] ?? null,
                    ];
                    $user->talent->update($aiProfileData);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Gemini API response is not in expected format.',
                        'raw_gemini_output' => $geminiOutput,
                        'debug_response_data' => $responseData
                    ], Response::HTTP_INTERNAL_SERVER_ERROR);
                }


                if (json_last_error() !== JSON_ERROR_NONE) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Failed to parse Gemini API response JSON.',
                        'raw_gemini_output' => $geminiOutput,
                        'debug_response_data' => $responseData
                    ], Response::HTTP_INTERNAL_SERVER_ERROR);
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'Profile suggestions generated successfully.',
                    'data' => $suggestions
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gemini API call returned an unexpected response.',
                    'api_response' => $responseData,
                    'status_code' => $statusCode,
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

        }   catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to connect to Gemini API: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // $user->talent->update($aiProfileData);

        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'AI-generated talent profile updated successfully',
        //     'data' => $user->talent
        // ], Response::HTTP_OK);
    }

    private function craftGeminiPrompt(array $data): string
    {

        $skills = implode(', ', $data['skills']);
        $interests = implode(', ', $data['interests']);
        $experiences = json_encode($data['experiences']);
        $projects = json_encode($data['projects']);
        $achievements = json_encode($data['achievements']);
        $education = json_encode($data['education_history']);

        return <<<PROMPT
            You are an AI-powered career coach, job suggestion and profile generator. Your task is to analyze a user's profile and suggest personalized goal career, career description, salary and job opportunity to help them figure out their career. Use indonesian language for the output.

            User Profile:
            - Full Name: {$data['full_name']}
            - Email: {$data['email']}
            - Current Education: {$data['current_education']}
            - Existing Skills: {$skills}
            - Work Experiences: {$experiences}
            - Projects: {$projects}
            - Achievements: {$achievements}
            - Interests: {$interests}
            - Education History: {$education}

            Based on the provided information, suggest goal career, career description, expected salary in IDR (Rupiah), and job opportunities that align with the user's profile. The suggestions should be realistic and tailored to the user's skills, experiences, and aspirations.
            1. Career Goal: Provide a clear and achievable career goal that aligns with the user's skills and interests. Example: Backend Developer, Data Scientist, etc.
            2. Career Description: Provide a brief description of the career goal, including key responsibilities in form of a paragraph.
            3. Expected Salary: Provide a realistic expected salary in IDR (Rupiah) based on the user's skills and experiences.
            4. Job Opportunities: Suggest a possible job opportunity that the user can pursue to achieve their career goal. Example: "Junior Backend Developer".

            Format your output as a JSON array of objects.
            Example:
            {
                "profiles": [
                    {
                        "goal_career": "Backend Developer",
                        "description": "Responsible for server-side web application logic and integration.",
                        "expected_salary": 10000000,
                        "job_opportunity": "Junior Backend Developer"
                    },
                    ...
                ]
            }
            Ensure the suggestions are tailored to the user's profile and career aspirations. Just provide only 1 suggestion job opportunity.

            PROMPT;
    }
}
