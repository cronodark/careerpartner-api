<?php

namespace App\Http\Controllers\talent;

use App\Http\Controllers\Controller;
use App\Models\User;
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
}
