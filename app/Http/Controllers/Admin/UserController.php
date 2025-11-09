<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    private string $module_name = 'User';

    function index(Request $request)
    {
        $query = User::orderBy('created_at', 'desc')
            ->where('role_id', '!=', 3)
            ->with('GetRole');
        $data['users'] = $query->whereNotIn('role_id', [RoleId(SuperAdminKey())])->paginate(10);
        $data['all_users_for_stats'] = User::where('role_id', '!=', 3)->whereNotIn('role_id', [RoleId(SuperAdminKey())])->get();
        $data['roles'] = Role::where('is_delete', '!=', 1)->where('status', 1)->get();
        $data['module_name'] = $this->module_name;
        return view('admin.user.index', $data);
    }

    function add(Request $request)
    {
        $rules['email'] = 'required|unique:users,email|max:50|email';
        $rules['password'] = 'required|min:6';
        $rules['confirm_password'] = 'required_with:password|same:password|min:6';
        $rules['phone'] = 'required|unique:users,phone';
        $rules['name'] = 'required|max:50|min:3';
        $rules['status'] = 'required';
        $rules['gender'] = 'required';

        $this->validate($request, $rules);

        $req['name'] = isset($request->name) ? $request->name : '';
        $req['phone'] = isset($request->phone) ? $request->phone : '';
        $req['gender'] = isset($request->gender) ? $request->gender : '';
        $req['role_id'] = isset($request->role_id) ? $request->role_id : '';
        $req['status'] = isset($request->status) ? $request->status : '';
        $req['password'] = isset($request->password) ? Hash::make($request->password) : '';
        $req['emp_id'] = Helper::CreateNewEmpId();
        $req['email'] = isset($request->email) ? $request->email : '';

        $is_saved = User::create($req);

        if ($is_saved) {
            $activity_params['added_by'] = Auth::user()->id;
            $activity_params['client_id'] = '';
            $activity_params['module'] = $this->module_name;
            $activity_params['action'] = 'add';
            $activity_params['table_name'] = 'users';
            $activity_params['description'] = 'add : ' . $req['name'];
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

    function delete(Request $request)
    {
        $id = $request->id;
        $method = $request->method();
        $is_deleted = 0;

        if ($method == "POST") {
            $exist_data = User::find($id);
            if (empty($exist_data)) {
                return redirect()->back()->with('alert-danger', 'Invalid ' . $this->module_name . '.');
            }

            if ($exist_data->is_delete == 1) {
                return redirect()->back()->with('alert-danger', $this->module_name . ' has been already deleted');
            }

            $delete['is_delete'] = 1;
            $is_delete = User::where('id', $id)->update($delete);

            if ($is_delete) {
                $activity_params['added_by'] = Auth::user()->id;
                $activity_params['client_id'] = '';
                $activity_params['module'] = $this->module_name;
                $activity_params['action'] = 'delete';
                $activity_params['table_name'] = 'users';
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

    public function form(Request $request)
    {
        $data['module_name'] = 'Add User';
        $id = isset($request->id) ? $request->id : '';
        if (!empty($id) && is_numeric($id)) {
            $data['module_name'] = 'Edit User';
            $is_exist = User::where('id', $id)->first();
            if (empty($is_exist)) {
                Session::flash('error', 'User Not Found..!');
                return redirect()->back();
            } else {
                $data['user'] = $is_exist;
            }
        }
        $role_query = Role::where('is_delete', '!=', 1)->where('status', 1);
        $role_query->whereNotIn('id', [RoleId(SuperAdminKey())]);
        $data['roles'] = $role_query->get();

        return view('admin.user.form', $data);
    }

    public function update(Request $request, $id)
    {
        // Validation and update logic for edit
        $is_exist = User::where('id', $id)->first();
        if (empty($is_exist)) {
            Session::flash('error', 'User Not Found..!');
            return redirect()->back();
        }

        $rules['phone'] = 'required';

        if (isset($request->password) && !empty($request->password)) {
            $rules['password'] = 'min:6';
            $rules['confirm_password'] = 'required_with:password|same:password|min:6';
        }

        $rules['name'] = 'required|max:50|min:3';
        $rules['status'] = 'required';
        $rules['gender'] = 'required';

        $this->validate($request, $rules);

        $req['name'] = isset($request->name) ? $request->name : '';
        $req['phone'] = isset($request->phone) ? $request->phone : '';
        $req['gender'] = isset($request->gender) ? $request->gender : '';
        $req['role_id'] = isset($request->role_id) ? $request->role_id : '';
        $req['status'] = isset($request->status) ? $request->status : '';

        if (isset($request->password) && !empty($request->password)) {
            $req['password'] = isset($request->password) ? Hash::make($request->password) : '';
        }

        $is_saved = User::where('id', $id)->update($req);

        if ($is_saved) {
            $activity_params['added_by'] = Auth::user()->id;
            $activity_params['client_id'] = '';
            $activity_params['module'] = $this->module_name;
            $activity_params['action'] = 'edit';
            $activity_params['table_name'] = 'users';
            $activity_params['description'] = 'edit : ' . $req['name'];
            $activity_params['ip'] = $request->ip();
            $activity_params['user_agent'] = UserDeviceDetails();
            $activity_params['data_after_action'] = json_encode($req);
            ActivityLog::ActivityLogCreate($activity_params);

            Session::flash('success', $this->module_name . ' Has Been Updated..!');
            return redirect()->back();
        } else {
            Session::flash('error', 'Something Went Wrong..!');
            return redirect()->back();
        }
    }

    function customers(Request $request)
    {
        $query = User::orderBy('created_at', 'desc')
            ->where('role_id', 3)
            ->with('GetRole');
        $data['users'] = $query->whereNotIn('role_id', [RoleId(SuperAdminKey())])->paginate(10);
        $data['all_customers_for_stats'] = User::where('role_id', 3)->whereNotIn('role_id', [RoleId(SuperAdminKey())])->get();
        $data['module_name'] = $this->module_name;
        return view('admin.user.customers', $data);
    }
}
