<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Module;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class RoleController extends Controller
{
    private string $module_name = 'Role';

    function index(Request $request)
    {
        $role_query = Role::whereNotIn('id', [RoleId(SuperAdminKey())]);
        $data['roles'] = $role_query->orderBy('created_at', 'desc')->paginate(10);
        $data['all_roles_for_stats'] = Role::whereNotIn('id', [RoleId(SuperAdminKey())])->get();
        $data['module_name'] = $this->module_name;
        return view('admin.roles.index', $data);
    }

    function add(Request $request)
    {
        $data['module_name'] = 'Add ' . $this->module_name;
        $id = isset($request->id) ? $request->id : '';
        if (!empty($id) && is_numeric($id)) {
            $data['module_name'] = 'Edit ' . $this->module_name;
            $role_exist = Role::where('id', $id)->first();
            if (empty($role_exist)) {
                Session::flash('error', $this->module_name . ' Not Found..!');
                return redirect()->back();
            } else {
                $data['role'] = $role_exist;
            }
        }

        if ($request->method() == 'post' || $request->method() == 'POST') {
            if (empty($id) || $id == '') {
                $rules['name'] = 'required|unique:roles';
                $rules['role_for'] = 'required';
            }
            $rules['status'] = 'required';
            $this->validate($request, $rules);

            $req['name'] = isset($request->name) ? $request->name : '';
            $req['status'] = isset($request->status) ? $request->status : '';

            if (!empty($id) && is_numeric($id)) {
                $is_saved = Role::where('id', $id)->update($req) !== false;
                $role_id = $id;
                $action = 'edit';
            } else {
                $req['role_for'] = isset($request->role_for) ? getSlug('roles', 'role_for', $request->role_for) : '';
                $role_saved = Role::create($req);
                if ($role_saved) {
                    $is_saved = true;
                    $role_id = $role_saved->id;
                    $action = 'add';
                } else {
                    $is_saved = false;
                }
            }

            if ($is_saved) {
                // Save permissions after role is saved
                $this->savePermission($request, $role_id);

                $activity_params['added_by'] = Auth::user()->id;
                $activity_params['client_id'] = '';
                $activity_params['module'] = $this->module_name;
                $activity_params['action'] = $action;
                $activity_params['table_name'] = 'roles';
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

        // Pass modules to the view
        $data['modules'] = Module::where('status', 'Active')->get();

        return view('admin.roles.form', $data);
    }

    private function savePermission($request, $id)
    {
        $permissionArr = (isset($request->permission)) ? $request->permission : [];
        $jsonData = json_encode($permissionArr);

        Role::where('id', $id)->update(['permission' => $jsonData]);
    }

    function delete(Request $request)
    {
        $id = $request->id;
        $method = $request->method();
        $is_deleted = 0;

        if ($method == "POST") {
            $exist_data = Role::find($id);
            if (empty($exist_data)) {
                return redirect()->back()->with('alert-danger', 'Invalid ' . $this->module_name . '.');
            }

            if ($exist_data->is_delete == 1) {
                return redirect()->back()->with('alert-danger', $this->module_name . ' has been already deleted');
            }

            $delete['is_delete'] = 1;
            $is_delete = Role::where('id', $id)->update($delete);

            if ($is_delete) {
                $activity_params['added_by'] = Auth::user()->id;
                $activity_params['client_id'] = '';
                $activity_params['module'] = $this->module_name;
                $activity_params['action'] = 'delete';
                $activity_params['table_name'] = 'roles';
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
