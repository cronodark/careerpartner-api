<?php

namespace App\Http\Controllers\talent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class InterestController extends Controller
{
    public function index(Request $request)
    {
        $talent = $request->user()->talent;
        return response()->json([
            'status' => 'success',
            'data' => [
                'interests' => $talent->interests,
            ]
        ]);
    }

    public function store(Request $request)
    {
        $talent = $request->user()->talent;
        $validator = Validator::make($request->all(), [
            'interests' => 'required|array',
            'interests.*.name' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $createdInterestsCount = 0;

        foreach ($validator->validated()['interests'] as $interestData) {
            $talent->interests()->create(['name' => $interestData['name']]);
            $createdInterestsCount++;
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Interest created successfully',
            'dataCount' => $createdInterestsCount,
        ], Response::HTTP_CREATED);
    }

    public function destroy(Request $request, $id)
    {
        $talent = $request->user()->talent;
        $interest = $talent->interests()->findOrFail($id);
        $interest->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Interest deleted successfully',
        ], Response::HTTP_OK);
    }
}
