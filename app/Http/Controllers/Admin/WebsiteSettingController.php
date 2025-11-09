<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;

class WebsiteSettingController extends Controller
{
    private string $module_name = 'Website Setting';

    function index(Request $request)
    {
        $query = WebsiteSetting::orderBy('created_at', 'desc');
        $data['website_settings'] = $query->get();
        $data['module_name'] = $this->module_name;

        self::clearCache();

        return view('admin.website_setting.index', $data);
    }

    function add(Request $request)
    {
        $data['module_name'] = 'Add ' . $this->module_name;
        $id = isset($request->id) ? $request->id : '';
        if (!empty($id) && is_numeric($id)) {
            $data['module_name'] = 'Edit ' . $this->module_name;
            $is_exist = WebsiteSetting::where('id', $id)->first();
            if (empty($is_exist)) {
                Session::flash('error', $this->module_name . ' Not Found..!');
                return redirect()->back();
            } else {
                $data['website_setting'] = $is_exist;
            }
        }

        if ($request->method() == 'post' || $request->method() == 'POST') {
            if (empty($id) || $id == '') {
                $rules['key'] = 'required|unique:website_settings,key|max:50';
            }

            $rules['name'] = 'required|max:50|min:3';
            $rules['type'] = 'required';

            $this->validate($request, $rules);

            $req['name'] = isset($request->name) ? $request->name : '';
            $req['type'] = isset($request->type) ? $request->type : '';
            $req['value'] = isset($request->value) ? $request->value : '';

            if ($request->type == 'file' && $request->hasFile('value')) {
                $rules['value'] = 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
                $this->validate($request, $rules);

                $file = $request->file('value');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/settings', $filename);
                $req['value'] = $filename;
            }

            if (!empty($id) && is_numeric($id)) {
                $is_saved = WebsiteSetting::where('id', $id)->update($req);
                $action = 'edit';
            } else {
                $req['key'] = isset($request->key) ? strtoupper(getSlug('website_settings', 'key', $request->key)) : '';
                $is_saved = WebsiteSetting::create($req);
                $action = 'add';
            }

            if ($is_saved) {
                $activity_params['added_by'] = Auth::user()->id;
                $activity_params['client_id'] = '';
                $activity_params['module'] = $this->module_name;
                $activity_params['action'] = $action;
                $activity_params['table_name'] = 'website_settings';
                $activity_params['description'] = $action . ' : ' . $req['name'];
                $activity_params['ip'] = $request->ip();
                $activity_params['user_agent'] = UserDeviceDetails();
                $activity_params['data_after_action'] = json_encode($req);
                ActivityLog::ActivityLogCreate($activity_params);


                Session::flash('success', $this->module_name . ' Has Been Save..!');
                //return redirect()->back();
                return redirect(url('admin/website_settings'));
            } else {
                Session::flash('error', 'Something Went Wrong..!');
                return redirect()->back();
            }
        }

        return view('admin.website_setting.form', $data);
    }

    function delete(Request $request)
    {
        $id = $request->id;
        $method = $request->method();
        $is_deleted = 0;

        if ($method == "POST") {
            $exist_data = WebsiteSetting::find($id);
            if (empty($exist_data)) {
                return redirect()->back()->with('alert-danger', 'Invalid ' . $this->module_name . '.');
            }

            if ($exist_data->is_delete == 1) {
                return redirect()->back()->with('alert-danger', $this->module_name . ' has been already deleted');
            }

            $delete['is_delete'] = 1;
            $is_delete = WebsiteSetting::where('id', $id)->update($delete);

            if ($is_delete) {
                $activity_params['added_by'] = Auth::user()->id;
                $activity_params['client_id'] = '';
                $activity_params['module'] = $this->module_name;
                $activity_params['action'] = 'delete';
                $activity_params['table_name'] = 'website_settings';
                $activity_params['description'] = 'delete : ' . $exist_data->name;
                $activity_params['ip'] = $request->ip();
                $activity_params['user_agent'] = UserDeviceDetails();
                $activity_params['data_after_action'] = '';
                ActivityLog::ActivityLogCreate($activity_params);

                Session::flash('success', $this->module_name . ' Has Been Deleted..!');
                return redirect()->back();
            } else {
                Session::flash('error', 'Something Went Wrong..!');
                return redirect()->back();
            }
        }
    }

    private function clearCache()
    {
        // Invalidate the cache for default image setting
        $cacheKey = 'crm_settings';
        Cache::forget($cacheKey);

        // Clear specific cache keys for site settings
        $siteSettings = WebsiteSetting::all();
        foreach ($siteSettings as $setting) {
            $cacheKey = 'website_setting_' . $setting->key;
            Cache::forget($cacheKey);
        }
    }

}
