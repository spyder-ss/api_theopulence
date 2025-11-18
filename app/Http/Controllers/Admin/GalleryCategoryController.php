<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\GalleryCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class GalleryCategoryController extends Controller
{
    private string $module_name = 'Gallery Categories';
    private string $table_name = 'gallery_categories';

    function index(Request $request)
    {
        $query = GalleryCategory::orderBy('created_at', 'desc');

        $data['model_data_lists'] = $query->get();

        $data['module_name'] = $this->module_name;
        return view('admin.gallery_categories.index', $data);
    }

    function add(Request $request)
    {
        $data['module_name'] = 'Add ' . $this->module_name;
        $id = isset($request->id) ? $request->id : '';
        if (!empty($id) && is_numeric($id)) {
            $data['module_name'] = 'Edit ' . $this->module_name;
            $is_exist = GalleryCategory::where('id', $id)->first();
            if (empty($is_exist)) {
                Session::flash('error', $this->module_name . ' Not Found..!');
                return redirect()->back();
            } else {
                $data['model_data'] = $is_exist;
            }
        }

        if ($request->method() == 'post' || $request->method() == 'POST') {
            $rules['name'] = 'required|string|max:255';
            $rules['sort_order'] = 'required|integer';
            $rules['status'] = 'required|boolean';
            $rules['field_configuration'] = 'nullable|array';

            if (!empty($id) && is_numeric($id)) {
                $rules['slug'] = 'required|string|max:255|unique:gallery_categories,slug,' . $id;
            } else {
                $rules['slug'] = 'required|string|max:255|unique:gallery_categories';
            }

            $this->validate($request, $rules);

            $req['parent_id'] = $request->parent_id ?? null;
            $req['name'] = $request->name ?? '';
            $req['sort_order'] = $request->sort_order ?? 0;
            $req['status'] = $request->status ?? 1;
            $req['field_configuration'] = $request->field_configuration ?? null;

            if (!empty($id) && is_numeric($id)) {
                $req['slug'] = Helper::GetSlug($this->table_name, 'slug', $id, $request->name);
                $is_saved = GalleryCategory::where('id', $id)->update($req);
                $action = 'edit';
                $category_id = $id;
            } else {
                $req['slug'] = Helper::GetSlug($this->table_name, 'slug', '', $request->name);
                $is_saved = GalleryCategory::create($req);
                $action = 'add';
                $category_id = $is_saved->id;
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

        $data['gallery_categories'] = GalleryCategory::where('status', 1)->get();
        return view('admin.gallery_categories.form', $data);
    }

    function delete(Request $request)
    {
        $id = $request->id;
        $method = $request->method();

        if ($method == "POST") {
            $exist_data = GalleryCategory::find($id);
            if (empty($exist_data)) {
                return redirect()->back()->with('alert-danger', 'Invalid ' . $this->module_name . '.');
            }

            $is_delete = GalleryCategory::where('id', $id)->delete();

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
}
