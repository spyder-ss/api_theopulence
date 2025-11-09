<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\CmsPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use File;
use Storage;

class CmsPageController extends Controller
{
    private string $module_name = 'Cms Pages';
    private string $table_name = 'Cms Pages';

    function index(Request $request)
    {
        $parent_id = isset($request->parent_id) ? $request->parent_id : '';

        $query = CmsPage::orderBy('created_at', 'desc');

        if (!empty($parent_id) && is_numeric($parent_id)) {
            $query->where('parent_id', $parent_id);
        } else {
            $query->whereIn('parent_id', [0, '']);
        }

        $data['model_data_lists'] = $query->get();

        $data['module_name'] = $this->module_name;
        return view('admin.cms_pages.index', $data);
    }

    function add(Request $request)
    {
        $data['module_name'] = 'Add ' . $this->module_name;
        $id = isset($request->id) ? $request->id : '';
        if (!empty($id) && is_numeric($id)) {
            $data['module_name'] = 'Edit ' . $this->module_name;
            $is_exist = CmsPage::where('id', $id)->first();
            if (empty($is_exist)) {
                Session::flash('error', $this->module_name . ' Not Found..!');
                return redirect()->back();
            } else {
                $data['model_data'] = $is_exist;
            }
        }

        if ($request->method() == 'post' || $request->method() == 'POST') {
            $rules['name'] = 'required';

            $ext = 'jpg,jpeg,png,gif';
            $ext2 = 'jpg,jpeg,png,gif,pdf';

            $rules['image'] = 'nullable|image|mimes:' . $ext;
            $rules['banner_image'] = 'nullable|image|mimes:' . $ext;
            $rules['mobile_banner_image'] = 'nullable|image|mimes:' . $ext;
            //$rules['document'] = 'nullable|mimes:' . $ext2;

            $this->validate($request, $rules);

            $req['parent_id'] = $request->parent_id ?? 0;
            $req['template'] = $request->template ?? '';
            $req['name'] = $request->name ?? '';
            $req['page_content'] = $request->page_content ?? '';
            $req['brief'] = $request->brief ?? '';
            $req['link'] = $request->link ?? '';
            $req['video_link'] = $request->video_link ?? '';
            $req['title'] = $request->title ?? '';
            $req['heading'] = $request->heading ?? '';
            $req['sub_heading'] = $request->sub_heading ?? '';
            $req['sort_order'] = $request->sort_order ?? 0;
            $req['status'] = $request->status ?? 1;
            $req['meta_title'] = $request->meta_title ?? '';
            $req['meta_keyword'] = $request->meta_keyword ?? '';
            $req['meta_description'] = $request->meta_description ?? '';

            //$req['image'] = $request->image ?? '';
            //$req['banner_image'] = $request->banner_image ?? '';
            //$req['mobile_banner_image'] = $request->mobile_banner_image ?? '';

            if (!empty($id) && is_numeric($id)) {
                //$req['slug'] = Helper::GetSlug('cms_pages', 'slug', '', $request->name);
                $is_saved = CmsPage::where('id', $id)->update($req);
                $action = 'edit';
                $cms_page_id = $id;
            } else {
                $req['slug'] = Helper::GetSlug('cms_pages', 'slug', '', $request->name);
                $is_saved = CmsPage::create($req);
                $action = 'add';
                $cms_page_id = $is_saved->id;
            }

            if ($is_saved) {
                $redirect_url = url(getAdminRouteName() . '/cms_pages/');

                $image = $request->file('image');
                if ($image) {
                    $photographs_mime_type = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $image);
                    if (!in_array($photographs_mime_type, array('image/png', 'image/jpeg'))) {
                        return redirect($redirect_url)->with('error', 'The image should be only jpeg,jpg or png type.');
                    }

                    if (filesize($image) >= 2000001) {
                        return redirect($redirect_url)->with('error', 'The image size should be not more than 2 MB.');
                    }

                    $image = $this->saveImage($image, $cms_page_id, 'image', $request);
                }

                $banner_image = $request->file('banner_image');
                if ($banner_image) {
                    $photographs_mime_type = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $banner_image);
                    if (!in_array($photographs_mime_type, array('image/png', 'image/jpeg'))) {
                        return redirect($redirect_url)->with('error', 'The image should be only jpeg,jpg or png type.');
                    }

                    if (filesize($banner_image) >= 2000001) {
                        return redirect($redirect_url)->with('error', 'The image size should be not more than 2 MB.');
                    }

                    $banner_image = $this->saveImage($banner_image, $cms_page_id, 'banner_image', $request);
                }

                $mobile_banner_image = $request->file('mobile_banner_image');
                if ($mobile_banner_image) {
                    $photographs_mime_type = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $mobile_banner_image);
                    if (!in_array($photographs_mime_type, array('image/png', 'image/jpeg'))) {
                        return redirect($redirect_url)->with('error', 'The image should be only jpeg,jpg or png type.');
                    }

                    if (filesize($mobile_banner_image) >= 2000001) {
                        return redirect($redirect_url)->with('error', 'The image size should be not more than 2 MB.');
                    }

                    $mobile_banner_image = $this->saveImage($mobile_banner_image, $cms_page_id, 'mobile_banner_image', $request);
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
                return redirect(url('admin/cms_pages'));
            } else {
                Session::flash('error', 'Something Went Wrong..!');
                return redirect()->back();
            }
        }

        // Template files - will be populated when theme system is implemented
        $data['files'] = [];

        return view('admin.cms_pages.form', $data);
    }

    function delete(Request $request)
    {
        $id = $request->id;
        $method = $request->method();
        $is_deleted = 0;

        if ($method == "POST") {
            $exist_data = CmsPage::find($id);
            if (empty($exist_data)) {
                return redirect()->back()->with('alert-danger', 'Invalid ' . $this->module_name . '.');
            }

            if ($exist_data->is_delete == 1) {
                return redirect()->back()->with('alert-danger', $this->module_name . ' has been already deleted');
            }

            $delete['is_delete'] = 1;
            $is_delete = CmsPage::where('id', $id)->update($delete);

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
        $is_exist = CmsPage::where('id', $id)->first();
        if (empty($is_exist)) {
            return false;
        }

        $result['org_name'] = '';
        $result['file_name'] = '';

        if ($file) {
            $path = 'cms_page/';
            $profile_dimension = Helper::WebsiteSettingsArray(['PROFILE_IMG_HEIGHT', 'PROFILE_IMG_WIDTH', 'PROFILE_THUMB_HEIGHT', 'PROFILE_THUMB_WIDTH']);

            $IMG_HEIGHT = $profile_dimension['PROFILE_IMG_HEIGHT']->value ?? 768;
            $IMG_WIDTH = $profile_dimension['PROFILE_IMG_WIDTH']->value ?? 768;
            $THUMB_HEIGHT = $profile_dimension['PROFILE_THUMB_HEIGHT']->value ?? 336;
            $THUMB_WIDTH = $profile_dimension['PROFILE_THUMB_WIDTH']->value ?? 336;

            $uploaded_data = Helper::UploadImage($file, $path, $IMG_HEIGHT, $IMG_WIDTH, $THUMB_HEIGHT, $THUMB_WIDTH, true, '', $id);

            if ($type == 'image') {
                $save_data['image'] = $uploaded_data['file_name'];
            } elseif ($type == 'banner_image') {
                $save_data['banner_image'] = $uploaded_data['file_name'];
            } elseif ($type == 'mobile_banner_image') {
                $save_data['mobile_banner_image'] = $uploaded_data['file_name'];
            }

            if ($uploaded_data['success']) {
                if (is_numeric($id) && ($id > 0)) {
                    $isSaved = CmsPage::where("id", $id)->update($save_data);

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
            $exist_data = CmsPage::find($id);
            if (empty($exist_data)) {
                return response()->json(['status' => 'error', 'message' => $type . ' not found.']);
            }

            if ($type == 'image') {
                if (file_exists('storage/cms_page/' . $id . '/' . $exist_data->image)) {
                    unlink('storage/cms_page/' . $id . '/' . $exist_data->image);
                }

                $req['image'] = '';
            } elseif ($type == 'banner_image') {
                if (file_exists('storage/cms_page/' . $id . '/' . $exist_data->banner_image)) {
                    unlink('storage/cms_page/' . $id . '/' . $exist_data->banner_image);
                }

                $req['banner_image'] = '';
            } elseif ($type == 'mobile_banner_image') {
                if (file_exists('storage/cms_page/' . $id . '/' . $exist_data->mobile_banner_image)) {
                    unlink('storage/cms_page/' . $id . '/' . $exist_data->mobile_banner_image);
                }

                $req['mobile_banner_image'] = '';
            }

            $is_save = CmsPage::where('id', $id)->update($req);

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
