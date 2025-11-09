<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Enquire;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    function index(Request $request)
    {
        $data['users_count'] = User::where('role_id', '!=', 1)->count();
        $data['blogs_count'] = Blog::where('is_delete', '!=', 1)->count();
        $data['testimonials_count'] = Testimonial::where('is_delete', '!=', 1)->count();
        $data['enquiries_count'] = Enquire::where('is_delete', '!=', 1)->count();

        return view('admin.dashboard.index', $data);
    }
}
