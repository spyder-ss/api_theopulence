<?php

namespace App\Helpers;

use App\Models\Module;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Helper
{
    public static function checkPermission($module_name, $permission_method)
    {
        if ($module_name === 'MANAGE-ADMIN' && $permission_method === 'super') {
            if (self::isSuperAdmin()) {
                return true;
            }

            abort(404, 'Not Found');
        }

        $permission_method_arr = explode('|', $permission_method);

        $roles_data = [];
        $moduleData = [];

        if (self::isSuperAdmin()) {
            return true;
        } else {
            if (auth()->user()->status == 0) {
                return false;
            }

            $is_role_exist = auth()->user()->GetRole;
            if (empty($is_role_exist) || ($is_role_exist->status != 1) || ($is_role_exist->is_delete == 1)) {
                return false;
            }

            $moduleData = \Cache::rememberForever("moduleData", function () {
                return Module::orderBy('updated_at', 'desc')->pluck('key');
            });

            $role_id = auth()->user()->role_id;
            $roles_data = \Cache::rememberForever("roleData-" . $role_id, function () use ($role_id) {
                return $data = DB::table('roles')->where('id', $role_id)->first();
            });

            if (!empty($roles_data->permission) && $roles_data->permission != null && !empty($moduleData)) {
                $permission = json_decode($roles_data->permission, true);
                foreach ($moduleData as $p_key) {
                    if ($module_name == $p_key) {
                        if (!empty($permission[$p_key])) {
                            if (count($permission_method_arr) > 1) {
                                foreach ($permission_method_arr as $key => $new_permission) {
                                    if (in_array($new_permission, $permission[$p_key])) {
                                        return true;
                                    }
                                }

                                return false;
                            } else {
                                if (in_array($permission_method, $permission[$p_key])) {
                                    return true;
                                } else {
                                    return false;
                                }
                            }
                        } else {
                            return false;
                        }
                    }
                }
            }

            return false;
        }
    }

    public static function isSuperAdmin()
    {
        if (!empty(Auth::user()) && Auth::user()->id == 1) {
            return true;
        }

        return false;
    }

    public static function CreateNewEmpId()
    {
        $prefix = 'EMP';
        $timestamp = now()->format('ymd');
        $sequence = rand(100, 999);
        return $prefix . $timestamp . $sequence;
    }

    public static function WebsiteSettingsArray($keys)
    {
        $settings = [];
        foreach ($keys as $key) {
            $setting = DB::table('website_settings')->where('key', $key)->first();
            $settings[$key] = $setting;
        }
        return $settings;
    }

    public static function GetSlug($table, $field_name, $exclude_id, $string)
    {
        $count = 1;

        // Convert to lowercase
        $lower_case_string = strtolower($string);

        // Remove special characters (keep letters, numbers, and spaces)
        $clean_string = preg_replace('/[^a-z0-9\s-]/', '', $lower_case_string);

        // Replace multiple spaces or hyphens with a single space
        $clean_string = preg_replace('/[\s-]+/', ' ', $clean_string);

        // Replace spaces with hyphens
        $slug = str_replace(' ', '-', trim($clean_string));

        // Check for duplicates
        $query = DB::table($table)->where($field_name, $slug);
        if (!empty($exclude_id)) {
            $query->where('id', '!=', $exclude_id);
        }

        $is_exist = $query->first();

        // If exists, append a number
        while (!empty($is_exist)) {
            $slug = $slug . '-' . $count;
            $query = DB::table($table)->where($field_name, $slug);
            if (!empty($exclude_id)) {
                $query->where('id', '!=', $exclude_id);
            }
            $is_exist = $query->first();
            $count++;
        }

        return $slug;
    }


    public static function UploadImage($file, $path, $imgHeight, $imgWidth, $thumbHeight, $thumbWidth, $createThumb = true, $oldFile = '', $folderName = '')
    {
        // Save to Laravel's storage/app/public/ directory (symlinked to public/storage/)
        $fullPath = storage_path('app/public/' . $path . $folderName);
        if (!is_dir($fullPath)) {
            mkdir($fullPath, 0755, true);
        }

        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->move($fullPath, $fileName);

        return [
            'success' => true,
            'file_name' => $fileName,
            'error' => ''
        ];
    }

    public static function MinifyHtml($html)
    {
        // Remove HTML comments (not conditional comments)
        $html = preg_replace('/<!--(?!\[if).*?-->/s', '', $html);

        // Remove extra whitespace between tags
        $html = preg_replace('/>\s+</', '><', $html);

        // Remove leading and trailing whitespace
        $html = trim($html);

        // Remove extra newlines and spaces
        $html = preg_replace('/\s+/', ' ', $html);

        return $html;
    }

    public static function getImageUrl($module, $id, $fileName)
    {
        if ($fileName && file_exists('storage/' . $module . '/' . $id . '/' . $fileName)) {
            return asset('storage/' . $module . '/' . $id . '/' . $fileName);
        }

        return '';
    }

    /* End of helper class */
}
