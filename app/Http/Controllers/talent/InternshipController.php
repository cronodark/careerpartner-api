<?php

namespace App\Http\Controllers\talent;

use App\Http\Controllers\Controller;
use App\Models\Internship;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InternshipController extends Controller
{
    public function index(){
        $internships = Internship::where('status', 'open')->orderBy('created_at', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'data' => $internships
        ], Response::HTTP_OK);
    }

    public function show($id)
    {
        $internship = Internship::find($id);
        $internship->load(['company', 'skills']);
        if (!$internship) {
            return response()->json([
                'status' => 'error',
                'message' => 'Internship not found'
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            'status' => 'success',
            'data' => $internship
        ], Response::HTTP_OK);
    }
}
