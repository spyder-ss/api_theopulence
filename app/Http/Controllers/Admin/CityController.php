<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Country;
use App\Models\City;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CityController extends Controller
{
    private string $module_name = 'City';

    function index(Request $request)
    {
        $parent_id = isset($request->parent_id) ? $request->parent_id : '';
        $query = City::orderBy('name', 'asc')
            ->with('countryDetails', 'stateDetails');
        $data['model_data_lists'] = $query->get();
        $data['module_name'] = $this->module_name;
        return view('admin.cities.index', $data);
    }

    function add(Request $request)
    {
        $data['module_name'] = 'Add ' . $this->module_name;
        $id = isset($request->id) ? $request->id : '';
        if (!empty($id) && is_numeric($id)) {
            $data['module_name'] = 'Edit ' . $this->module_name;
            $is_exist = City::where('id', $id)->first();
            if (empty($is_exist)) {
                Session::flash('error', $this->module_name . ' Not Found..!');
                return redirect()->back();
            } else {
                $data['model_data'] = $is_exist;
            }
        }

        if ($request->method() == 'post' || $request->method() == 'POST') {
            if (empty($id) || $id == '') {
                $rules['name'] = 'required|unique:cities,name|max:50';
            } else {
                $rules['name'] = 'required|max:100';
            }
            $rules['country_id'] = 'required|max:100';
            $rules['state_id'] = 'required|max:100';

            $is_country_exist = Country::where('status', 1)
                ->where('id', $request->country_id)
                ->first();
            if (empty($is_country_exist)) {
                Session::flash('error', 'Country Not Found..!');
                return redirect()->back();
            }

            $is_state_exist = State::where('status', 1)
                ->where('id', $request->state_id)
                ->where('country_id', $request->country_id)
                ->first();
            if (empty($is_state_exist)) {
                Session::flash('error', 'State Not Found..!');
                return redirect()->back();
            }

            $this->validate($request, $rules);
            $req['name'] = isset($request->name) ? $request->name : '';
            $req['country_id'] = isset($request->country_id) ? $request->country_id : '';
            $req['state_id'] = isset($request->state_id) ? $request->state_id : '';

            if (!empty($id) && is_numeric($id)) {
                $is_saved = City::where('id', $id)->update($req);
                $action = 'edit';
            } else {
                $is_saved = City::create($req);
                $action = 'add';
            }

            if ($is_saved) {
                $activity_params['added_by'] = Auth::user()->id;
                $activity_params['client_id'] = '';
                $activity_params['module'] = $this->module_name;
                $activity_params['action'] = $action;
                $activity_params['table_name'] = 'cities';
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
        $data['countries'] = Country::where('status', 1)->get();
        $data['states'] = State::where('status', 1)->get();
        return view('admin.cities.form', $data);
    }

    function delete(Request $request)
    {
        $id = $request->id;
        $method = $request->method();
        $is_deleted = 0;

        if ($method == "POST") {
            $exist_data = City::find($id);
            if (empty($exist_data)) {
                return redirect()->back()->with('alert-danger', 'Invalid ' . $this->module_name . '.');
            }

            if ($exist_data->is_delete == 1) {
                return redirect()->back()->with('alert-danger', $this->module_name . ' has been already deleted');
            }

            $delete['is_delete'] = 1;
            $is_delete = City::where('id', $id)->update($delete);

            if ($is_delete) {
                $activity_params['added_by'] = Auth::user()->id;
                $activity_params['client_id'] = '';
                $activity_params['module'] = $this->module_name;
                $activity_params['action'] = 'delete';
                $activity_params['table_name'] = 'cities';
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
