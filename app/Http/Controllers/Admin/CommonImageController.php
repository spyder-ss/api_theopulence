<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\CommonImage;
use App\Models\CommonImageCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class CommonImageController extends Controller
{
    private string $module_name = 'Common Images';
    private string $table_name = 'common_images';

    public function index()
    {
        $images = CommonImage::with('category')->latest()->get();
        $commonImageCategories = CommonImageCategory::where('status', 1)->get(); // Fetch categories
        $data['model_data_lists'] = $images;
        $data['module_name'] = $this->module_name;
        $data['commonImageCategories'] = $commonImageCategories; // Pass categories to the view
        return view('admin.common_images.index', $data);
    }

    public function add(Request $request)
    {
        $data['module_name'] = 'Add ' . $this->module_name;
        $id = isset($request->id) ? $request->id : '';
        $categories = CommonImageCategory::where('status', 1)->get();
        $data['categories'] = $categories;

        if (!empty($id) && is_numeric($id)) {
            $data['module_name'] = 'Edit ' . $this->module_name;
            $is_exist = CommonImage::where('id', $id)->first();
            if (empty($is_exist)) {
                Session::flash('error', $this->module_name . ' Not Found..!');
                return redirect()->back();
            } else {
                $data['model_data'] = $is_exist;
            }
        }

        if ($request->method() == 'post' || $request->method() == 'POST') {
            $rules = [
                'common_image_category_id' => 'required|exists:common_image_categories,id',
                'alt_text' => 'nullable|string|max:255',
                'sort_order' => 'required|integer',
                'status' => 'required|boolean',
            ];

            $ext = 'jpg,jpeg,png,gif,svg';
            if (empty($id)) {
                $rules['images'] = 'required|array';
                $rules['images.*'] = 'image|mimes:' . $ext . '|max:10240'; // Increased to 10MB
            } else {
                $rules['images'] = 'nullable|array';
                $rules['images.*'] = 'image|mimes:' . $ext . '|max:10240'; // Increased to 10MB
            }

            $this->validate($request, $rules);

            $req_data = $request->except('images', '_token');

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = 'common_images/';
                    $uploaded_data = Helper::UploadImage($image, $path, 768, 768, 336, 336, true, '', $request->common_image_category_id);
                    if ($uploaded_data['success']) {
                        $req_data['image'] = $uploaded_data['file_name'];
                        $is_saved = CommonImage::create($req_data);

                        // Check if this is the first image in its category
                        $image_count = CommonImage::where('common_image_category_id', $req_data['common_image_category_id'])->count();
                        if ($image_count === 1) {
                            $is_saved->is_main_image = true;
                            $is_saved->save();
                        }
                    }
                }
                $action = 'add';
            }

            if (!empty($id) && is_numeric($id)) {
                $is_saved = CommonImage::where('id', $id)->update($req_data);
                if ($request->hasFile('images')) {
                    $image = $request->file('images')[0];
                    $path = 'common_images/';
                    $commonImage = CommonImage::find($id);
                    $category_id = $commonImage ? $commonImage->common_image_category_id : '';
                    $uploaded_data = Helper::UploadImage($image, $path, 768, 768, 336, 336, true, '', $category_id);
                    if ($uploaded_data['success']) {
                        $req_data['image'] = $uploaded_data['file_name'];
                        CommonImage::where('id', $id)->update(['image' => $req_data['image']]);
                    }
                }
                $action = 'edit';
            }

            $activity_params['module'] = $this->module_name;
            $activity_params['action'] = $action;
            $activity_params['table_name'] = $this->table_name;
            $activity_params['description'] = $action . ' : ' . ($request->alt_text ?? 'N/A');
            $activity_params['ip'] = $request->ip();
            $activity_params['user_agent'] = UserDeviceDetails();
            $activity_params['data_after_action'] = json_encode($req_data);
            ActivityLog::ActivityLogCreate($activity_params);

            Session::flash('success', $this->module_name . ' Has Been Save..!');
            return redirect()->back();
        }

        return view('admin.common_images.form', $data);
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        $method = $request->method();

        if ($method == "POST") {
            $exist_data = CommonImage::find($id);
            if (empty($exist_data)) {
                return redirect()->back()->with('alert-danger', 'Invalid ' . $this->module_name . '.');
            }

            // Delete image from storage
            if ($exist_data->image) {
                $category_id = $exist_data->common_image_category_id;
                Storage::delete('public/common_images/' . $category_id . '/' . $exist_data->image);
            }

            $is_delete = $exist_data->delete();

            if ($is_delete) {
                $activity_params['added_by'] = Auth::user()->id;
                $activity_params['client_id'] = '';
                $activity_params['module'] = $this->module_name;
                $activity_params['action'] = 'delete';
                $activity_params['table_name'] = $this->table_name;
                $activity_params['description'] = 'delete : ' . ($exist_data->alt_text ?? 'N/A');
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

    public function markAsMain(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:common_images,id',
        ]);

        $imageIds = $request->input('ids');

        // First, find the category of the first image to scope the update
        $firstImage = CommonImage::find($imageIds[0]);
        if (!$firstImage) {
            return response()->json(['status' => 'error', 'message' => 'Image not found.']);
        }
        $categoryId = $firstImage->common_image_category_id;

        // Unset all main images in the same category
        CommonImage::where('common_image_category_id', $categoryId)
            ->update(['is_main_image' => false]);

        // Set the selected images as main
        CommonImage::whereIn('id', $imageIds)
            ->where('common_image_category_id', $categoryId)
            ->update(['is_main_image' => true]);

        $activity_params['module'] = $this->module_name;
        $activity_params['action'] = 'mark as main';
        $activity_params['table_name'] = $this->table_name;
        $activity_params['description'] = 'Updated main images for category ID: ' . $categoryId;
        $activity_params['ip'] = $request->ip();
        $activity_params['user_agent'] = UserDeviceDetails();
        $activity_params['data_after_action'] = json_encode($imageIds);
        ActivityLog::ActivityLogCreate($activity_params);

        return response()->json(['status' => 'ok', 'message' => 'Main images updated successfully.']);
    }

    function ajax_img_delete(Request $request)
    {
        $id = isset($request->id) ? $request->id : '';
        $type = isset($request->type) ? $request->type : '';

        $method = $request->method();
        $req = [];

        if ($method == "POST") {
            $exist_data = CommonImage::find($id);
            if (empty($exist_data)) {
                return response()->json(['status' => 'error', 'message' => $type . ' not found.']);
            }

            if ($type == 'image') {
                if ($exist_data->image) {
                    $category_id = $exist_data->common_image_category_id;
                    Storage::delete('public/common_images/' . $category_id . '/' . $exist_data->image);
                }
                $req['image'] = '';
            }

            $is_save = CommonImage::where('id', $id)->update($req);

            if ($is_save) {
                $activity_params['added_by'] = Auth::user()->id;
                $activity_params['client_id'] = '';
                $activity_params['module'] = $this->module_name;
                $activity_params['action'] = 'delete';
                $activity_params['table_name'] = $this->table_name;
                $activity_params['description'] = 'delete : ' . ($exist_data->alt_text ?? 'N/A') . ' image';
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
