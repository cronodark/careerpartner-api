<?php

namespace App\Http\Controllers\talent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class EducationHistoryController extends Controller
{
    public function index(){
        $talent = auth()->user()->talent;
        if (!$talent) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => 'success',
            'data' => $talent->educationHistories,
        ], Response::HTTP_OK);

    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'institution_name' => 'required|string|max:255',
            'field_of_study' => 'required|string|max:255',
            'start_year' => 'required|integer|digits:4|before_or_equal:today',
            'end_year' => 'nullable|integer|digits:4|after_or_equal:start_year',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $talent = auth()->user()->talent;

        if (!$talent) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan',
            ], Response::HTTP_NOT_FOUND);
        }

        $talent->educationHistories()->create($validator->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Riwayat pendidikan berhasil ditambahkan',
        ], Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'institution_name' => 'sometimes|required|string|max:255',
            'field_of_study' => 'sometimes|required|string|max:255',
            'start_year' => 'sometimes|required|integer|digits:4|before_or_equal:today',
            'end_year' => 'sometimes|required|nullable|integer|digits:4|after_or_equal:start_year',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $talent = auth()->user()->talent;

        if (!$talent) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan',
            ], Response::HTTP_NOT_FOUND);
        }

        $educationHistory = $talent->educationHistories()->find($id);

        if (!$educationHistory) {
            return response()->json([
                'status' => 'error',
                'message' => 'Riwayat pendidikan tidak ditemukan',
            ], Response::HTTP_NOT_FOUND);
        }

        $educationHistory->update($validator->validated());

        

        return response()->json([
            'status' => 'success',
            'message' => 'Riwayat pendidikan berhasil diperbarui',
        ], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $talent = auth()->user()->talent;

        if (!$talent) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan',
            ], Response::HTTP_NOT_FOUND);
        }

        $educationHistory = $talent->educationHistories()->find($id);

        if (!$educationHistory) {
            return response()->json([
                'status' => 'error',
                'message' => 'Riwayat pendidikan tidak ditemukan',
            ], Response::HTTP_NOT_FOUND);
        }

        $educationHistory->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Riwayat pendidikan berhasil dihapus',
        ], Response::HTTP_OK);
    }
}
