<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Enquire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EnquiryApiController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'type' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'message' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $enquiry = Enquire::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'type' => $request->type,
            'address' => $request->address,
            'message' => $request->message,
            'is_read' => 0,
            'is_delete' => 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Enquiry submitted successfully',
            'data' => $enquiry
        ], 201);
    }
}
