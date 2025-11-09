<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use App\Models\ActivityLog;
use App\Models\GalleryCategory;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class GalleryImageController extends Controller
{
    private string $module_name = 'Gallery Images';
    private string $table_name = 'gallery_images';

    public function index()
    {
        $images = GalleryImage::with('category')->latest()->get();
        $data['model_data_lists'] = $images;
        $data['module_name'] = $this->module_name;
        return view('admin.gallery_images.index', $data);
    }

    public function add(Request $request)
    {
        $data['module_name'] = 'Add ' . $this->module_name;
        $id = isset($request->id) ? $request->id : '';
        $categories = GalleryCategory::where('status', 1)->get();
        $data['categories'] = $categories;
        $data['fieldConfiguration'] = [];

        if (!empty($id) && is_numeric($id)) {
            $data['module_name'] = 'Edit ' . $this->module_name;
            $is_exist = GalleryImage::where('id', $id)->first();
            if (empty($is_exist)) {
                Session::flash('error', $this->module_name . ' Not Found..!');
                return redirect()->back();
            } else {
                $data['model_data'] = $is_exist;
                $data['fieldConfiguration'] = $is_exist->category->field_configuration ?? [];
            }
        }

        if ($request->method() == 'post' || $request->method() == 'POST') {
            $rules = [
                'gallery_category_id' => 'required|exists:gallery_categories,id',
                'title' => 'nullable|string|max:255',
                'subtitle' => 'nullable|string|max:255',
                'brief' => 'nullable|string',
                'description' => 'nullable|string',
                'sort_order' => 'required|integer',
                'status' => 'required|boolean',
            ];

            $ext = 'jpg,jpeg,png,gif,svg';
            if (empty($id)) {
                $rules['image'] = 'required|image|mimes:' . $ext . '|max:2048';
            } else {
                $rules['image'] = 'nullable|image|mimes:' . $ext . '|max:2048';
            }

            $this->validate($request, $rules);

            $req_data = $request->except('image', '_token');
            $req_data['image'] = null;

            if (!empty($id) && is_numeric($id)) {
                $is_saved = GalleryImage::where('id', $id)->update($req_data);
                $action = 'edit';
                $gallery_image_id = $id;
            } else {
                $is_saved = GalleryImage::create($req_data);
                $action = 'add';
                $gallery_image_id = $is_saved->id;
            }

            if ($is_saved) {
                $redirect_url = url(getAdminRouteName() . '/gallery-images/');

                $image = $request->file('image');
                if ($image) {
                    $photographs_mime_type = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $image);
                    if (!in_array($photographs_mime_type, array('image/png', 'image/jpeg', 'image/gif', 'image/svg+xml'))) {
                        return redirect($redirect_url)->with('error', 'The image should be only jpeg,jpg,png,gif or svg type.');
                    }

                    if (filesize($image) >= 2000001) {
                        return redirect($redirect_url)->with('error', 'The image size should be not more than 2 MB.');
                    }

                    $this->saveImage($image, $gallery_image_id, 'image', $request);
                }

                $activity_params['added_by'] = Auth::user()->id;
                $activity_params['client_id'] = '';
                $activity_params['module'] = $this->module_name;
                $activity_params['action'] = $action;
                $activity_params['table_name'] = $this->table_name;
                $activity_params['description'] = $action . ' : ' . ($request->title ?? 'N/A');
                $activity_params['ip'] = $request->ip();
                $activity_params['user_agent'] = UserDeviceDetails();
                $activity_params['data_after_action'] = json_encode($req_data);
                ActivityLog::ActivityLogCreate($activity_params);

                Session::flash('success', $this->module_name . ' Has Been Save..!');
                return redirect()->back();
            } else {
                Session::flash('error', 'Something Went Wrong..!');
                return redirect()->back();
            }
        }

        return view('admin.gallery_images.form', $data);
    }

    /**
     * Display the specified resource.
     */
    public function show(GalleryImage $galleryImage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request)
    {
        $id = $request->id;
        $method = $request->method();
        $is_deleted = 0;

        if ($method == "POST") {
            $exist_data = GalleryImage::find($id);
            if (empty($exist_data)) {
                return redirect()->back()->with('alert-danger', 'Invalid ' . $this->module_name . '.');
            }

            // Delete image from storage
            if ($exist_data->image) {
                Storage::delete('public/gallery/' . $exist_data->id . '/' . $exist_data->image);
            }

            $is_delete = $exist_data->delete();

            if ($is_delete) {
                $activity_params['added_by'] = Auth::user()->id;
                $activity_params['client_id'] = '';
                $activity_params['module'] = $this->module_name;
                $activity_params['action'] = 'delete';
                $activity_params['table_name'] = $this->table_name;
                $activity_params['description'] = 'delete : ' . ($exist_data->title ?? 'N/A');
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
        $is_exist = GalleryImage::where('id', $id)->first();
        if (empty($is_exist)) {
            return false;
        }

        $result['org_name'] = '';
        $result['file_name'] = '';

        if ($file) {
            $path = 'gallery/';
            $profile_dimension = Helper::WebsiteSettingsArray(['PROFILE_IMG_HEIGHT', 'PROFILE_IMG_WIDTH', 'PROFILE_THUMB_HEIGHT', 'PROFILE_THUMB_WIDTH']);

            $IMG_HEIGHT = $profile_dimension['PROFILE_IMG_HEIGHT']->value ?? 768;
            $IMG_WIDTH = $profile_dimension['PROFILE_IMG_WIDTH']->value ?? 768;
            $THUMB_HEIGHT = $profile_dimension['PROFILE_THUMB_HEIGHT']->value ?? 336;
            $THUMB_WIDTH = $profile_dimension['PROFILE_THUMB_WIDTH']->value ?? 336;

            $uploaded_data = Helper::UploadImage($file, $path, $IMG_HEIGHT, $IMG_WIDTH, $THUMB_HEIGHT, $THUMB_WIDTH, true, '', $id);

            if ($type == 'image') {
                $save_data['image'] = $uploaded_data['file_name'];
            }

            if ($uploaded_data['success']) {
                if (is_numeric($id) && ($id > 0)) {
                    $isSaved = GalleryImage::where("id", $id)->update($save_data);

                    if ($isSaved) {
                        $activity_params['added_by'] = Auth::user()->id;
                        $activity_params['client_id'] = '';
                        $activity_params['module'] = $this->module_name . ' Image';
                        $activity_params['action'] = 'edit';
                        $activity_params['table_name'] = $this->table_name;
                        $activity_params['description'] = 'edit : ' . $type;
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
            $exist_data = GalleryImage::find($id);
            if (empty($exist_data)) {
                return response()->json(['status' => 'error', 'message' => $type . ' not found.']);
            }

            if ($type == 'image') {
                if ($exist_data->image) {
                    Storage::delete('public/gallery/' . $exist_data->id . '/' . $exist_data->image);
                }
                $req['image'] = '';
            }

            $is_save = GalleryImage::where('id', $id)->update($req);

            if ($is_save) {
                $activity_params['added_by'] = Auth::user()->id;
                $activity_params['client_id'] = '';
                $activity_params['module'] = $this->module_name;
                $activity_params['action'] = 'delete';
                $activity_params['table_name'] = $this->table_name;
                $activity_params['description'] = 'delete : ' . ($exist_data->title ?? 'N/A') . ' image';
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
