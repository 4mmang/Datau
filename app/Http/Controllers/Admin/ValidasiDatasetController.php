<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValidasiDatasetController extends Controller
{
    public function valid($id)
    {
        $dataset = Dataset::findOrFail($id);
        $dataset->status = 'valid';
        $dataset->note = '-';
        $dataset->update();
        return response()->json([
            'message' => 'success',
        ]);
    }

    public function invalid(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'note' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors()->first(),
            ]);
        }

        $dataset = Dataset::findOrFail($id);
        $dataset->status = 'invalid';
        $dataset->note = $request->note;
        $dataset->update();
        return response()->json([
            'status' => 200,
            'message' => 'invalid',
        ]);
    }
}
