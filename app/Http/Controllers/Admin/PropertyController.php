<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Amenity;
use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PropertyController extends Controller
{
    private string $module_name = 'Properties';
    private string $table_name = 'properties';
    function index(Request $request)
    {
        $query = Property::where('is_delete', 0)->orderBy('created_at', 'desc');

        $data['model_data_lists'] = $query->get();

        $data['module_name'] = $this->module_name;
        return view('admin.properties.index', $data);
    }

    function add(Request $request)
    {
        $data['module_name'] = 'Add ' . $this->module_name;
        $id = isset($request->id) ? $request->id : '';
        if (!empty($id) && is_numeric($id)) {
            $data['module_name'] = 'Edit ' . $this->module_name;
            $is_exist = Property::where('id', $id)->first();
            if (empty($is_exist)) {
                Session::flash('error', $this->module_name . ' Not Found..!');
                return redirect()->back();
            } else {
                $data['model_data'] = $is_exist;
            }
        }

        $data['amenities'] = Amenity::where('status', 1)->get();

        if ($request->method() == 'post' || $request->method() == 'POST') {
            $rules['title'] = 'required';

            $ext = 'jpg,jpeg,png,gif';
            $rules['images'] = 'nullable|array';
            $rules['images.*'] = 'image|mimes:' . $ext;
            $this->validate($request, $rules);

            $req['title'] = $request->title ?? '';
            $req['location'] = $request->location ?? '';
            $req['guest_capacity'] = $request->guest_capacity ?? 0;
            $req['bedrooms'] = $request->bedrooms ?? 0;
            $req['bathrooms'] = $request->bathrooms ?? 0;
            $req['property_brief'] = $request->property_brief ?? '';
            $req['property_description'] = $request->property_description ?? '';
            $req['property_experience'] = $request->property_experience ?? '';
            $req['spaces'] = $request->spaces ?? '';
            $req['cancellation_policy'] = $request->cancellation_policy ?? '';
            $req['other_important_information'] = $request->other_important_information ?? '';
            $req['faqs'] = $request->faqs ?? '';
            $req['status'] = $request->status ?? 1;

            if (!empty($id) && is_numeric($id)) {
                $req['slug'] = Helper::GetSlug('properties', 'slug', '', $request->title);
                $is_saved = Property::where('id', $id)->update($req);
                $action = 'edit';
                $cms_page_id = $id;
            } else {
                $req['slug'] = Helper::GetSlug('properties', 'slug', '', $request->title);
                $is_saved = Property::create($req);
                $action = 'add';
                $cms_page_id = $is_saved->id;
            }

            if ($is_saved) {
                // Handle amenities
                if (!empty($id) && is_numeric($id)) {
                    $property = Property::find($id);
                } else {
                    $property = $is_saved;
                }

                if ($request->has('amenities')) {
                    $property->amenities()->sync($request->amenities);
                } else {
                    $property->amenities()->detach();
                }

                $redirect_url = url(getAdminRouteName() . '/properties/');

                $images = $request->file('images');
                if ($images) {
                    foreach ($images as $image) {
                        $photographs_mime_type = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $image);
                        if (!in_array($photographs_mime_type, array('image/png', 'image/jpeg'))) {
                            return redirect($redirect_url)->with('error', 'The image should be only jpeg,jpg or png type.');
                        }

                        if (filesize($image) >= 2000001) {
                            return redirect($redirect_url)->with('error', 'The image size should be not more than 2 MB.');
                        }

                        $image = $this->saveImage($image, $cms_page_id, 'image', $request);
                    }
                }

                // Set main image if provided
                if ($request->has('main_image_id')) {
                    $property->images()->update(['is_main' => false]);
                    PropertyImage::where('id', $request->main_image_id)->update(['is_main' => true]);
                } else {
                    $firstImage = $property->images()->first();
                    if ($firstImage) {
                        $property->images()->update(['is_main' => false]);
                        $firstImage->update(['is_main' => true]);
                    }
                }

                $activity_params['added_by'] = Auth::user()->id;
                $activity_params['client_id'] = '';
                $activity_params['module'] = $this->module_name;
                $activity_params['action'] = $action;
                $activity_params['table_name'] = $this->table_name;
                $activity_params['description'] = $action . ' : ' . $req['title'];
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

        return view('admin.properties.form', $data);
    }

    function delete(Request $request)
    {
        $id = $request->id;
        $method = $request->method();
        $is_deleted = 0;

        if ($method == "POST") {
            $exist_data = Property::find($id);
            if (empty($exist_data)) {
                return redirect()->back()->with('alert-danger', 'Invalid ' . $this->module_name . '.');
            }

            if ($exist_data->is_delete == 1) {
                return redirect()->back()->with('alert-danger', $this->module_name . ' has been already deleted');
            }

            $delete['is_delete'] = 1;
            $is_delete = Property::where('id', $id)->update($delete);

            if ($is_delete) {
                $activity_params['added_by'] = Auth::user()->id;
                $activity_params['client_id'] = '';
                $activity_params['module'] = $this->module_name;
                $activity_params['action'] = 'delete';
                $activity_params['table_name'] = $this->table_name;
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

    public function saveImage($file, $id, $type, $request)
    {
        $is_exist = Property::where('id', $id)->first();
        if (empty($is_exist)) {
            return false;
        }

        $result['org_name'] = '';
        $result['file_name'] = '';

        if ($file) {
            $path = 'property_images/';
            $profile_dimension = Helper::WebsiteSettingsArray(['PROFILE_IMG_HEIGHT', 'PROFILE_IMG_WIDTH', 'PROFILE_THUMB_HEIGHT', 'PROFILE_THUMB_WIDTH']);

            $IMG_HEIGHT = $profile_dimension['PROFILE_IMG_HEIGHT']->value ?? 768;
            $IMG_WIDTH = $profile_dimension['PROFILE_IMG_WIDTH']->value ?? 768;
            $THUMB_HEIGHT = $profile_dimension['PROFILE_THUMB_HEIGHT']->value ?? 336;
            $THUMB_WIDTH = $profile_dimension['PROFILE_THUMB_WIDTH']->value ?? 336;

            $uploaded_data = Helper::UploadImage($file, $path, $IMG_HEIGHT, $IMG_WIDTH, $THUMB_HEIGHT, $THUMB_WIDTH, true, '', $id);

            if ($type == 'image') {
                $save_data['image_path'] = $uploaded_data['file_name'];
                $save_data['property_id'] = $id;
                $save_data['is_main'] = false;
            }

            if ($uploaded_data['success']) {
                if (is_numeric($id) && ($id > 0)) {
                    $isSaved = PropertyImage::create($save_data);

                    if ($isSaved) {
                        $activity_params['added_by'] = Auth::user()->id;
                        $activity_params['client_id'] = '';
                        $activity_params['module'] = $this->module_name . ' Image';
                        $activity_params['action'] = 'add';
                        $activity_params['table_name'] = 'property_images';
                        $activity_params['description'] = 'add : ' . $type;
                        $activity_params['ip'] = $request->ip();
                        $activity_params['user_agent'] = UserDeviceDetails();
                        $activity_params['data_after_action'] = json_encode($save_data);
                        ActivityLog::ActivityLogCreate($activity_params);
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
            $exist_data = PropertyImage::find($id);
            if (empty($exist_data)) {
                return response()->json(['status' => 'error', 'message' => $type . ' not found.']);
            }

            if ($type == 'image') {
                if (file_exists('storage/property_images/' . $exist_data->property_id . '/' . $exist_data->image_path)) {
                    unlink('storage/property_images/' . $exist_data->property_id . '/' . $exist_data->image_path);
                }
                $req['image_path'] = '';
                $is_save = PropertyImage::where('id', $id)->delete();
            }

            if ($is_save) {
                $activity_params['added_by'] = Auth::user()->id;
                $activity_params['client_id'] = '';
                $activity_params['module'] = $this->module_name;
                $activity_params['action'] = 'delete';
                $activity_params['table_name'] = 'property_images';
                $activity_params['description'] = 'delete : ' . $exist_data->property->title . ' image';
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
