<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::select('id', 'slug', 'title', 'image', 'brief', 'posted_on')
            ->where('status', 1)
            ->where('is_delete', 0)
            ->orderBy('sort_order', 'asc')
            ->orderBy('posted_on', 'desc');

        $blogs = $query->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'slug' => $item->slug,
                'title' => $item->title,
                'image' => Helper::getImageUrl('blog', $item->id, $item->image),
                'brief' => $item->brief,
                'posted_on' => $item->posted_on ? date('d-m-Y', strtotime($item->posted_on)) : ''
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $blogs
        ]);
    }

    public function show(Request $request, $slug)
    {
        $blog = Blog::where('slug', $slug)
            ->where('status', 1)
            ->where('is_delete', 0)
            ->first(['id', 'slug', 'title', 'image', 'brief', 'description', 'author', 'posted_on', 'link', 'video_link', 'sort_order', 'meta_title', 'meta_keyword', 'meta_description']);

        if (!$blog) {
            return response()->json([
                'success' => false,
                'message' => 'Blog not found'
            ], 404);
        }

        $blog->image = Helper::getImageUrl('blog', $blog->id, $blog->image);

        return response()->json([
            'success' => true,
            'data' => $blog
        ]);
    }
}
