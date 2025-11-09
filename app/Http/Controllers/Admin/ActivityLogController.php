<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ActivityLogController extends Controller
{
    private string $module_name = 'Activity Log';

    function index(Request $request)
    {
        $query = ActivityLog::orderBy('created_at', 'desc');
        $data['activity_logs'] = $query->with('GetAddedBy')->get();
        $data['module_name'] = $this->module_name;
        return view('admin.activity_log.index', $data);
    }

    function details(Request $request)
    {
        $id = isset($request->id) ? $request->id : '';
        $is_exist = ActivityLog::where('id', $id)->with('GetAddedBy')->first();
        if (empty($is_exist)) {
            Session::flash('success', $this->module_name . ' Has Been Save..!');
            return redirect()->back();
        }
        $data['activity_log'] = $is_exist;
        $data['module_name'] = $this->module_name . ' Details';
        return view('admin.activity_log.details', $data);
    }

}
