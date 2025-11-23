<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\CommonImageCategory;
use App\Models\CommonImage; // Added this import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CommonImageCategoryController extends Controller
{
    private string $module_name = 'Common Image Categories';
    private string $table_name = 'common_image_categories';

    function index(Request $request)
    {
        $query = CommonImageCategory::orderBy('created_at', 'desc');

        $data['model_data_lists'] = $query->get();

        $data['module_name'] = $this->module_name;
        return view('admin.common_image_categories.index', $data);
    }

    function add(Request $request)
    {
        $data['module_name'] = 'Add ' . $this->module_name;
        $id = isset($request->id) ? $request->id : '';
        if (!empty($id) && is_numeric($id)) {
            $data['module_name'] = 'Edit ' . $this->module_name;
            $is_exist = CommonImageCategory::where('id', $id)->first();
            if (empty($is_exist)) {
                Session::flash('error', $this->module_name . ' Not Found..!');
                return redirect()->back();
            } else {
                $data['model_data'] = $is_exist;
            }
        }

        if ($request->method() == 'post' || $request->method() == 'POST') {
            $rules['name'] = 'required|string|max:255';
            $rules['status'] = 'required|boolean';
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048';

            $this->validate($request, $rules);

            $req['name'] = $request->name ?? '';
            $req['title'] = $request->title ?? null;
            $req['brief'] = $request->brief ?? null;
            $req['status'] = $request->status ?? 1;

            if ($request->hasFile('image')) {
                $path = 'common_image_categories/';
                $uploaded_data = Helper::UploadImage($request->file('image'), $path, 400, 400, null, null);
                if ($uploaded_data['success']) {
                    $req['image'] = $uploaded_data['file_name'];
                }
            }

            if (!empty($id) && is_numeric($id)) {
                $is_saved = CommonImageCategory::where('id', $id)->update($req);
                $action = 'edit';
            } else {
                $req['slug'] = Helper::GetSlug('common_image_categories', 'slug', $id, $request->name);
                $is_saved = CommonImageCategory::create($req);
                $action = 'add';
            }

            if ($is_saved) {
                $activity_params['added_by'] = Auth::user()->id;
                $activity_params['client_id'] = '';
                $activity_params['module'] = $this->module_name;
                $activity_params['action'] = $action;
                $activity_params['table_name'] = $this->table_name;
                $activity_params['description'] = $action . ' : ' . $req['name'];
                $activity_params['ip'] = $request->ip();
                $activity_params['user_agent'] = UserDeviceDetails();
                $activity_params['data_after_action'] = json_encode($req);
                ActivityLog::ActivityLogCreate($activity_params);

                Session::flash('success', $this->module_name . ' Has Been Save..!');
                return redirect()->back();
            } else {
                Session::flash('error', 'Something Went Wrong..!');
                return redirect()->back();
            }
        }

        return view('admin.common_image_categories.form', $data);
    }

    function delete(Request $request)
    {
        $id = $request->id;

        $exist_data = CommonImageCategory::find($id);
        if (empty($exist_data)) {
            return redirect()->back()->with('alert-danger', 'Invalid ' . $this->module_name . '.');
        }

        $has_images = CommonImage::where('common_image_category_id', $id)->exists();

        if ($has_images) {
            Session::flash('error', 'Cannot delete ' . $this->module_name . ' as it has associated common images. Please delete the images first.');
            return redirect()->back();
        }

        $is_delete = CommonImageCategory::where('id', $id)->delete();

        if ($is_delete) {
            $activity_params['added_by'] = Auth::user()->id;
            $activity_params['client_id'] = '';
            $activity_params['module'] = $this->module_name;
            $activity_params['action'] = 'delete';
            $activity_params['table_name'] = $this->table_name;
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
