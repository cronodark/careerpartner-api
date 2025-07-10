<?php

namespace App\Http\Controllers\talent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = auth()->user()->talent->projects;
        return response()->json([
            'status' => 'success',
            'data' => $projects,
        ], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'link' => 'required|url',
            'year' => 'required|integer|digits:4',
        ]);

        $validatedData = $validator->validated();

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('projects', 'public');
            $validatedData['image'] = $imagePath;
        }

        $project = auth()->user()->talent->projects()->create($validatedData);

        return response()->json([
            'status' => 'success',
            'message' => 'Project berhasil ditambahkan',
        ], Response::HTTP_CREATED);
    }

    public function destroy($id)
    {
        $project = auth()->user()->talent->projects()->find($id);

        if (!$project) {
            return response()->json([
                'status' => 'error',
                'message' => 'Project not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $project->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Project deleted successfully',
        ], Response::HTTP_OK);
    }
}
