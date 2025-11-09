<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GalleryCategory;
use Illuminate\Http\Request;
use App\Helpers\Helper;

class GalleryApiController extends Controller
{
    public function categories()
    {
        $categories = GalleryCategory::where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->get(['id', 'name', 'slug']);

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }

    public function images($slug)
    {
        $category = GalleryCategory::where('slug', $slug)->where('status', 1)->first();

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found',
            ], 404);
        }

        $images = $category->images()
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->get(['id', 'title', 'subtitle', 'brief', 'description', 'image'])
            ->map(function ($image) use ($category) {
                $fieldConfiguration = $category->field_configuration ?? [];

                $image->image = Helper::getImageUrl('gallery', $image->id, $image->image);

                // Conditionally unset fields based on category configuration
                if (!($fieldConfiguration['title'] ?? false)) {
                    unset($image->title);
                }
                if (!($fieldConfiguration['subtitle'] ?? false)) {
                    unset($image->subtitle);
                }
                if (!($fieldConfiguration['brief'] ?? false)) {
                    unset($image->brief);
                }
                if (!($fieldConfiguration['description'] ?? false)) {
                    unset($image->description);
                }

                return $image;
            });

        return response()->json([
            'success' => true,
            'data' => [
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                ],
                'images' => $images,
            ],
        ]);
    }
}
