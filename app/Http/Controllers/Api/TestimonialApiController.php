<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Testimonial::with('property')
            ->where('status', 1)
            ->where('is_delete', 0)
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc');

        $testimonials = $query->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                // 'designation' => $item->designation,
                'description' => $item->description,
                'image' => Helper::getImageUrl('testimonials', $item->id, $item->image),
                'sort_order' => $item->sort_order,
                'rating' => $item->rating,
                'property_id' => $item->property->title ?? null,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $testimonials
        ]);
    }
}
