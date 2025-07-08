<?php

namespace App\Http\Controllers\talent;

use App\Http\Controllers\Controller;
use App\Models\Internship;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InternshipController extends Controller
{
    public function index(){
        $internships = Internship::orderBy('created_at', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'data' => $internships
        ], Response::HTTP_OK);
    }
}
