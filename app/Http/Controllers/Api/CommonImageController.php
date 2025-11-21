<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CommonImage;
use App\Models\CommonImageCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommonImageController extends Controller
{
    public function categories(): JsonResponse
    {
        $categories = CommonImageCategory::where('status', 1)
            ->get(['id', 'name', 'title', 'brief']);

        return response()->json([
            'status' => true,
            'message' => 'Common image categories fetched successfully.',
            'data' => $categories
        ]);
    }

    public function images(string $slug): JsonResponse
    {
        $category = CommonImageCategory::where('slug', $slug)->where('status', 1)->first();

        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Common image category not found or inactive.',
                'data' => []
            ], 404);
        }

        $images = CommonImage::where('common_image_category_id', $category->id)
            ->where('status', 1)
            ->get(['id', 'image', 'alt_text', 'sort_order']);

        return response()->json([
            'status' => true,
            'message' => 'Common images fetched successfully.',
            'data' => $images
        ]);
    }
}
