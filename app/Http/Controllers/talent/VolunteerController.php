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
        $volunteer = VolunteerActivity::orderBy('created_at', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'data' => $volunteer
        ], Response::HTTP_OK);
    }
}
