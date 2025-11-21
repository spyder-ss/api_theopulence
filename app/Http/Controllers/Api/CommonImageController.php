<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CommonImage;
use App\Models\CommonImageCategory;
use Illuminate\Http\JsonResponse;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class CommonImageController extends Controller
{
    public function categories(): JsonResponse
    {
        $categories = CommonImageCategory::where('status', 1)
            ->get(['id', 'slug', 'name', 'title', 'brief']);

        $categories->map(function ($category) {
            $mainImage = CommonImage::where('common_image_category_id', $category->id)
                ->where('is_main_image', true)
                ->where('status', 1)
                ->first();

            if ($mainImage && $mainImage->image) {
                $category->main_image_url = Helper::getImageUrl('common_images', $category->id, $mainImage->image);
            } else {
                $category->main_image_url = null;
            }

            return $category;
        });

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

    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'id' => 'required|exists:common_images,id',
            'field' => 'required|in:title,subtitle,is_main_image,sort_order,status,alt_text,common_image_category_id',
            'value' => 'nullable',
        ]);

        $image = CommonImage::find($request->id);
        if (!$image) {
            return response()->json(['status' => false, 'message' => 'Image not found.'], 404);
        }

        if ($request->field === 'is_main_image') {
            if ((bool) $request->value) {
                CommonImage::where('common_image_category_id', $image->common_image_category_id)
                    ->where('id', '!=', $image->id)
                    ->update(['is_main_image' => false]);
            }
            $image->{$request->field} = (bool) $request->value;
        } else if ($request->field === 'sort_order') {
            $image->{$request->field} = (int) $request->value;
        } else if ($request->field === 'status') {
            $image->{$request->field} = (bool) $request->value;
        } else if ($request->field === 'common_image_category_id') {
            $image->{$request->field} = (int) $request->value;
        } else {
            $image->{$request->field} = $request->value;
        }

        $image->save();

        return response()->json(['status' => true, 'message' => 'Field updated successfully.']);
    }
}
