<?php

namespace App\Http\Controllers\talent;

use App\Http\Controllers\Controller;
use App\Models\VolunteerActivity;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VolunteerController extends Controller
{
    public function index()
    {
        $volunteer = VolunteerActivity::where('status', 'open')->orderBy('created_at', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'data' => $volunteer
        ], Response::HTTP_OK);
    }

    public function show($id)
    {
        $volunteer = VolunteerActivity::find($id);
        $volunteer->load(['organization', 'skills']);
        if (!$volunteer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Volunteer activity not found'
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            'status' => 'success',
            'data' => $volunteer
        ], Response::HTTP_OK);
    }
}
