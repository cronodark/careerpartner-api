<?php

namespace App\Http\Controllers\talent;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use LDAP\Result;

class LearningPathController extends Controller
{
    public function index()
    {
        $learningPath = auth()->user()->talent->learningPaths()->get();

        return response()->json([
            'status' => 'success',
            'data' => $learningPath
        ], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'url' => 'required|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $learningPath = auth()->user()->learningPaths()->create([
            'title' => $request->input('title'),
            'url' => $request->input('url'),
            'is_done' => false,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $learningPath
        ], Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        $learningPath = auth()->user()->learningPaths()->findOrFail($id);

        if (!$learningPath) {
            return response()->json([
                'status' => 'error',
                'message' => 'Learning path not found.'
            ], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'is_done' => 'sometimes|required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $learningPath->update($request->all());

        return response()->json([
            'status' => 'success',
            'data' => $learningPath
        ], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $learningPath = auth()->user()->learningPaths()->findOrFail($id);

        if ($learningPath) {
            $learningPath->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Learning path deleted successfully.'
            ], Response::HTTP_OK);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Learning path not found.'
        ], Response::HTTP_NOT_FOUND);
    }

    public function generate(Request $request)
    {
        $user = auth()->user();
        if (!$user->talent || !$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Talent profile not found.'
            ], Response::HTTP_NOT_FOUND);
        }

        $talent = $user->talent;
        $talent->load([
            'skills',
            'experiences',
            'projects',
            'achievements',
            'interests',
            'educationHistories'
        ]);

        $promptData = [
            'full_name' => $user->full_name,
            'email' => $user->email,
            'current_education' => $talent->current_education,
            'goal_career' => $talent->goal_career,
            'description' => $talent->description,
            'expected_salary' => $talent->expected_salary,
            'skills' => $talent->skills->pluck('name')->toArray(),
            'experiences' => $talent->experiences->toArray(),
            'projects' => $talent->projects->toArray(),
            'achievements' => $talent->achievements->toArray(),
            'interests' => $talent->interests->pluck('name')->toArray(),
            'education_history' => $talent->educationHistories->toArray(),
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

                if($suggestions['learning_paths']) {
                    $learningPathCounter = 0;
                    foreach($suggestions['learning_paths'] as $learningPath) {
                        auth()->user()->talent->learningPaths()->create($learningPath);
                        $learningPathCounter++;
                    }
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Learning path suggestions generated successfully.',
                        'dataCreated' => $learningPathCounter,
                    ], Response::HTTP_OK);
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
                    'message' => 'Learning path suggestions generated successfully.',
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
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to connect to Gemini API: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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
            You are an AI-powered career coach and learning path generator. Your task is to analyze a user's profile and suggest personalized learning paths to help them achieve their career goals. Use indonesian language for the output.

            User Profile:
            - Full Name: {$data['full_name']}
            - Email: {$data['email']}
            - Current Education: {$data['current_education']}
            - Desired Career Goal: {$data['goal_career']}
            - Self-Description: {$data['description']}
            - Expected Salary: {$data['expected_salary']}
            - Existing Skills: {$skills}
            - Work Experiences: {$experiences}
            - Projects: {$projects}
            - Achievements: {$achievements}
            - Interests: {$interests}
            - Education History: {$education}

            Based on the provided information, suggest 3-5 distinct learning paths. Each suggestion should:
            1. Have a concise "title" (e.g., "Mastering Advanced React Development").
            2. Provide a platform where the learning path can be accessed (e.g., "https://example.com/learning-path").

            Format your output as a JSON array of objects.
            Example:
            {
                "learning_paths": [
                    {
                        "title": "Mastering Advanced React Development",
                        "url": "https://example.com/learning-path",
                    },
                    ...
                ]
            }
            Ensure the suggestions are tailored to the user's profile and career aspirations.

            PROMPT;
    }
}
