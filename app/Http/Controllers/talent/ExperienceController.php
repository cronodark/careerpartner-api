<?php

namespace App\Http\Controllers\talent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ExperienceController extends Controller
{
    public function index(Request $request)
    {
        $talent = $request->user()->talent;
        return response()->json([
            'status' => 'success',
            'data' => [
                'experience' => $talent->experiences,
            ]
        ], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'experiences' => 'required|array',
            'experiences.*.description' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $experiencesCount = 0;

        foreach ($request->input('experiences') as $experienceData) {
            $experience = $request->user()->talent->experiences()->create([
                'description' => $experienceData['description'],
            ]);
            $experiencesCount++;
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Experience added successfully',
            'dataCount' => $experiencesCount,
        ]);

    }

    public function destroy(Request $request, $id)
    {
        $experience = $request->user()->talent->experiences()->find($id);

        if (!$experience) {
            return response()->json([
                'status' => 'error',
                'message' => 'Experience not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $experience->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Experience deleted successfully',
        ], Response::HTTP_OK);
    }
}
