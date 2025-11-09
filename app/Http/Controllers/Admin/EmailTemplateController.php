<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class EmailTemplateController extends Controller
{
    private string $module_name = 'Email Template';

    function index(Request $request)
    {
        $query = EmailTemplate::orderBy('created_at', 'desc');
        $data['email_templates'] = $query->get();
        $data['module_name'] = $this->module_name;
        return view('admin.email_template.index', $data);
    }

    function add(Request $request)
    {
        $data['module_name'] = 'Add ' . $this->module_name;
        $id = isset($request->id) ? $request->id : '';
        if (!empty($id) && is_numeric($id)) {
            $data['module_name'] = 'Edit ' . $this->module_name;
            $is_exist = EmailTemplate::where('id', $id)->first();
            if (empty($is_exist)) {
                Session::flash('error', $this->module_name . ' Not Found..!');
                return redirect()->back();
            } else {
                $data['email_template'] = $is_exist;
            }
        }

        if ($request->method() == 'post' || $request->method() == 'POST') {
            if (empty($id) || $id == '') {
                $rules['key'] = 'required|unique:email_templates,key|max:50';
            }
            $rules['title'] = 'required|max:50|min:3';
            $this->validate($request, $rules);

            $req['title'] = isset($request->title) ? $request->title : '';
            $req['value'] = isset($request->value) ? Helper::MinifyHtml($request->value) : '';

            if (!empty($id) && is_numeric($id)) {
                $is_saved = EmailTemplate::where('id', $id)->update($req);
                $action = 'edit';
            } else {
                $req['key'] = isset($request->key) ? strtoupper(getSlug('email_templates', 'key', $request->key)) : '';
                $is_saved = EmailTemplate::create($req);
                $action = 'add';
            }

            if ($is_saved) {
                $activity_params['added_by'] = Auth::user()->id;
                $activity_params['client_id'] = '';
                $activity_params['module'] = $this->module_name;
                $activity_params['action'] = $action;
                $activity_params['table_name'] = 'email_templates';
                $activity_params['description'] = $action . ' : ' . $req['title'];
                $activity_params['ip'] = $request->ip();
                $activity_params['user_agent'] = UserDeviceDetails();
                $activity_params['data_after_action'] = json_encode($req);
                ActivityLog::ActivityLogCreate($activity_params);

                Session::flash('success', $this->module_name . ' Has Been Save..!');
                return redirect(url('admin/email_templates'));
            } else {
                Session::flash('error', 'Something Went Wrong..!');
                return redirect()->back();
            }
        }

        return view('admin.email_template.form', $data);
    }

    function delete(Request $request)
    {
        $id = $request->id;
        $method = $request->method();
        $is_deleted = 0;

        if ($method == "POST") {
            $exist_data = EmailTemplate::find($id);
            if (empty($exist_data)) {
                return redirect()->back()->with('alert-danger', 'Invalid ' . $this->module_name . '.');
            }

            if ($exist_data->is_delete == 1) {
                return redirect()->back()->with('alert-danger', $this->module_name . ' has been already deleted');
            }

            $delete['is_delete'] = 1;
            $is_delete = EmailTemplate::where('id', $id)->update($delete);

            if ($is_delete) {
                $activity_params['added_by'] = Auth::user()->id;
                $activity_params['client_id'] = '';
                $activity_params['module'] = $this->module_name;
                $activity_params['action'] = 'delete';
                $activity_params['table_name'] = 'email_templates';
                $activity_params['description'] = 'delete : ' . $exist_data->title;
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
