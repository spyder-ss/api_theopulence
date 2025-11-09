<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TestimonialController extends Controller
{
    private string $module_name = 'Testimonial';
    private string $table_name = 'testimonials';

    function index(Request $request)
    {
        $parent_id = isset($request->parent_id) ? $request->parent_id : '';
        $query = Testimonial::orderBy('created_at', 'desc');
        $data['model_data_lists'] = $query->get();
        $data['module_name'] = $this->module_name;
        return view('admin.testimonials.index', $data);
    }

    function add(Request $request)
    {
        $data['module_name'] = 'Add ' . $this->module_name;
        $id = isset($request->id) ? $request->id : '';
        if (!empty($id) && is_numeric($id)) {
            $data['module_name'] = 'Edit ' . $this->module_name;
            $is_exist = Testimonial::where('id', $id)->first();
            if (empty($is_exist)) {
                Session::flash('error', $this->module_name . ' Not Found..!');
                return redirect()->back();
            } else {
                $data['model_data'] = $is_exist;
            }
        }

        if ($request->method() == 'post' || $request->method() == 'POST') {
            $rules['name'] = 'required';

            $this->validate($request, $rules);

            $req['name'] = $request->name ?? '';
            $req['designation'] = $request->designation ?? '';
            $req['video'] = $request->video ?? '';
            $req['testimonial'] = $request->testimonial ?? '';
            $req['featured'] = $request->featured ?? '0';
            $req['sort_order'] = $request->sort_order ?? '';
            $req['status'] = $request->status ?? '1';

            if (!empty($id) && is_numeric($id)) {
                $is_saved = Testimonial::where('id', $id)->update($req);
                $action = 'edit';
                $testimonial_id = $id;
            } else {
                $is_saved = Testimonial::create($req);
                $action = 'add';
                $testimonial_id = $is_saved->id;
            }

            if ($is_saved) {
                $redirect_url = url(getAdminRouteName() . '/testimonials/');
                $file = $request->file('image');
                if ($file) {
                    // Validate image type and size
                    $rules['image'] = 'nullable|image|mimes:jpeg,jpg,png|max:2048'; // 2MB = 2048KB
                    $imageValidator = \Illuminate\Support\Facades\Validator::make($request->all(), ['image' => $rules['image']]);

                    if ($imageValidator->fails()) {
                        return redirect($redirect_url)->with('error', $imageValidator->errors()->first('image'));
                    }

                    $image_result = $this->saveImage($file, $testimonial_id, 'main_photo', $request);
                }

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
                return redirect(url('admin/testimonials'));
            } else {
                Session::flash('error', 'Something Went Wrong..!');
                return redirect()->back();
            }
        }

        return view('admin.testimonials.form', $data);
    }

    function delete(Request $request)
    {
        $id = $request->id;
        $method = $request->method();
        $is_deleted = 0;

        if ($method == "POST") {
            $exist_data = Testimonial::find($id);
            if (empty($exist_data)) {
                return redirect()->back()->with('alert-danger', 'Invalid ' . $this->module_name . '.');
            }

            if ($exist_data->is_delete == 1) {
                return redirect()->back()->with('alert-danger', $this->module_name . ' has been already deleted');
            }

            // Delete associated image file if exists
            if (!empty($exist_data->image) && file_exists('storage/testimonials/' . $id . '/' . $exist_data->image)) {
                unlink('storage/testimonials/' . $id . '/' . $exist_data->image);
            }

            $delete['is_delete'] = 1;
            $is_delete = Testimonial::where('id', $id)->update($delete);

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

    public function saveImage($file, $id, $type, $request)
    {
        $is_exist = Testimonial::where('id', $id)->first();
        if (empty($is_exist)) {
            return false;
        }

        $result['org_name'] = '';
        $result['file_name'] = '';

        if ($file) {
            $path = 'testimonials/';
            $profile_dimension = Helper::WebsiteSettingsArray(['PROFILE_IMG_HEIGHT', 'PROFILE_IMG_WIDTH', 'PROFILE_THUMB_HEIGHT', 'PROFILE_THUMB_WIDTH']);

            $IMG_HEIGHT = $profile_dimension['PROFILE_IMG_HEIGHT']->value ?? 768;
            $IMG_WIDTH = $profile_dimension['PROFILE_IMG_WIDTH']->value ?? 768;
            $THUMB_HEIGHT = $profile_dimension['PROFILE_THUMB_HEIGHT']->value ?? 336;
            $THUMB_WIDTH = $profile_dimension['PROFILE_THUMB_WIDTH']->value ?? 336;

            $uploaded_data = Helper::UploadImage($file, $path, $IMG_HEIGHT, $IMG_WIDTH, $THUMB_HEIGHT, $THUMB_WIDTH, true, '', $id);
            $save_data['image'] = $uploaded_data['file_name'];

            if ($uploaded_data['success']) {
                if (is_numeric($id) && ($id > 0)) {
                    // Delete old image if exists
                    $old_image = $is_exist->image;
                    if (!empty($old_image) && file_exists('storage/testimonials/' . $id . '/' . $old_image)) {
                        unlink('storage/testimonials/' . $id . '/' . $old_image);
                    }

                    $isSaved = Testimonial::where("id", $id)->update($save_data);

                    if ($isSaved) {
                        $activity_params['added_by'] = Auth::user()->id;
                        $activity_params['client_id'] = '';
                        $activity_params['module'] = $this->module_name . ' Image';
                        $activity_params['action'] = 'edit';
                        $activity_params['table_name'] = $this->table_name;
                        $activity_params['description'] = 'edit : ' . $save_data['image'];
                        $activity_params['ip'] = $request->ip();
                        $activity_params['user_agent'] = UserDeviceDetails();
                        $activity_params['data_after_action'] = json_encode($save_data);
                        ActivityLog::ActivityLogCreate($activity_params);
                        return true;
                    }
                }
            }
        }

        return false;
    }

    function ajax_img_delete(Request $request)
    {
        $id = isset($request->id) ? $request->id : '';
        $type = isset($request->type) ? $request->type : '';

        $method = $request->method();
        $req = [];

        if ($method == "POST") {
            $exist_data = Testimonial::find($id);
            if (empty($exist_data)) {
                return response()->json(['status' => 'error', 'message' => $type . ' not found.']);
            }

            if ($type == 'image') {
                if (file_exists('storage/testimonials/' . $id . '/' . $exist_data->image)) {
                    unlink('storage/testimonials/' . $id . '/' . $exist_data->image);
                }
                $req['image'] = '';
            }

            $is_save = Testimonial::where('id', $id)->update($req);

            if ($is_save) {
                $activity_params['added_by'] = Auth::user()->id;
                $activity_params['client_id'] = '';
                $activity_params['module'] = $this->module_name;
                $activity_params['action'] = 'delete';
                $activity_params['table_name'] = $this->table_name;
                $activity_params['description'] = 'delete : ' . $exist_data->name . ' image';
                $activity_params['ip'] = $request->ip();
                $activity_params['user_agent'] = UserDeviceDetails();
                $activity_params['data_after_action'] = '';
                ActivityLog::ActivityLogCreate($activity_params);

                return response()->json(['status' => 'ok', 'message' => $type . ' has been deleted..!']);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Something Went Wrong..!']);
            }
        }
    }

}
