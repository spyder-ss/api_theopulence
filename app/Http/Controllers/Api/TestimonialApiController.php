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
        $query = Testimonial::where('status', 1)
            ->where('is_delete', 0)
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc');

        $testimonials = $query->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'designation' => $item->designation,
                'image' => Helper::getImageUrl('testimonials', $item->id, $item->image),
                'video' => $item->video,
                'testimonial' => $item->testimonial,
                'sort_order' => $item->sort_order,
                'featured' => $item->featured,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $testimonials
        ]);
    }
}
