<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Inquiry\StoreRequest;
use App\Models\Admin\Inquiry\Inquiry;
use Illuminate\Http\JsonResponse;

class InquiryController extends Controller
{
    /**
     * Store a newly created inquiry in storage.
     */
    public function store(StoreRequest $request): JsonResponse
    {
        try {
            $inquiry = Inquiry::create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Inquiry submitted successfully.',
                'data' => $inquiry
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit inquiry. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
