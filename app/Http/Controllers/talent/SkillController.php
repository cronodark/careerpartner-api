<?php

namespace App\Http\Controllers\talent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class SkillController extends Controller
{
    public function index(Request $request)
    {
        $talent = $request->user()->talent;
        return response()->json([
            'status' => 'success',
            'data' => [
                'skills' => $talent->skills,
            ]
        ]);
    }

    public function store(Request $request)
    {
        $talent = $request->user()->talent;
        $validator = Validator::make($request->all(), [
            'skills' => 'required|array',
            'skills.*.name' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $createdSkillsCount = 0;

        foreach ($validator->validated()['skills'] as $skillData) {
            $talent->skills()->create([
                'name' => $skillData['name']
            ]);
            $createdSkillsCount++;
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Skill created successfully',
            'dataCount' => $createdSkillsCount,
        ], Response::HTTP_CREATED);
    }

    public function destroy(Request $request, $id)
    {
        $talent = $request->user()->talent;
        $skill = $talent->skills()->findOrFail($id);
        $skill->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Skill deleted successfully',
        ], Response::HTTP_OK);
    }
}
