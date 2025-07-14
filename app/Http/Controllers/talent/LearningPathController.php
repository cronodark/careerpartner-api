<?php

namespace App\Http\Controllers\talent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use LDAP\Result;

class LearningPathController extends Controller
{
    public function index()
    {
        $learninngPath = auth()->user()->learningPaths()->get();

        return response()->json([
            'status' => 'success',
            'data' => $learninngPath
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

        $learninngPath = auth()->user()->learningPaths()->create([
            'title' => $request->input('title'),
            'url' => $request->input('url'),
            'is_done' => false,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $learninngPath
        ], Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        $learninngPath = auth()->user()->learningPaths()->findOrFail($id);

        if (!$learninngPath) {
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

        $learninngPath->update($request->all());

        return response()->json([
            'status' => 'success',
            'data' => $learninngPath
        ], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $learninngPath = auth()->user()->learningPaths()->findOrFail($id);

        if ($learninngPath) {
            $learninngPath->delete();
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
}
