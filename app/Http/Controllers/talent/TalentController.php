<?php

namespace App\Http\Controllers\talent;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class TalentController extends Controller
{
    public function index()
    {
        $talent = auth()->user()->talent->load([
            'skills',
            'experiences',
            'projects',
            'achievements',
            'interests',
            'educationHistories'
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
        if($data->fails()) {
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
