<?php

namespace App\Http\Controllers\talent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class AchievementController extends Controller
{
    public function index(Request $request)
    {
        $talent = $request->user()->talent;
        return response()->json([
            'status' => 'success',
            'data' => [
                'achievements' => $talent->achievements,
            ]
        ], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'achievements' => 'sometimes|required|array',
            'achievements.*.title' => 'sometimes|required|string|max:255',
            'achievements.*.nomination' => 'sometimes|required|string|max:500',
            'achievements.*.year' => 'sometimes|required|integer|min:1900|max:' . date('Y'),

            'title' => 'sometimes|required|string|max:255',
            'nomination' => 'sometimes|required|string|max:500',
            'year' => 'sometimes|required|integer|min:1900|max:' . date('Y'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $talent = $request->user()->talent;

        if ($request->has('achievements')) {
            $achievementCount = 0;
            foreach ($request->achievements as $achievementData) {
                $talent->achievements()->create($achievementData);
                $achievementCount++;
            }

            return response()->json([
                'status' => 'success',
                'message' => "$achievementCount achievements created successfully",
            ], Response::HTTP_CREATED);

        } else {
            $talent->achievements()->create($request->only(['title', 'nomination', 'year']));
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Achievements created successfully',
        ], Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'nomination' => 'sometimes|required|string|max:500',
            'year' => 'sometimes|required|integer|min:1900|max:' . date('Y'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $achievement = auth()->user()->talent->achievements()->find($id);

        if (!$achievement) {
            return response()->json([
                'status' => 'error',
                'message' => 'Achievement not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $achievement->update($validator->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Achievement updated successfully',
        ], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $achievement = auth()->user()->talent->achievements()->find($id);

        if (!$achievement) {
            return response()->json([
                'status' => 'error',
                'message' => 'Achievement not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $achievement->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Achievement deleted successfully',
        ], Response::HTTP_OK);
    }
}
