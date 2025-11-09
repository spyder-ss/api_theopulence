<?php
use App\Models\Role;
// use Jenssegers\Agent\Facades\Agent; // Commented out due to missing package

if (!function_exists('getAdminRouteName')) {
    function getAdminRouteName()
    {
        $ADMIN_ROUTE_NAME = config('custom.ADMIN_ROUTE_NAME');

        if (empty($ADMIN_ROUTE_NAME)) {
            $ADMIN_ROUTE_NAME = 'admin';
        }

        return $ADMIN_ROUTE_NAME;
    }
}

if (!function_exists('SuperAdminKey')) {
    function SuperAdminKey()
    {
        return 'super-admin';
    }
}

if (!function_exists('getCurrentPageUrl')) {
    function getCurrentPageUrl()
    {
        return $_SERVER['REQUEST_URI'];
    }
}

if (!function_exists('RoleId')) {
    function RoleId($key)
    {
        if (!empty(Auth::user())) {
            $is_role_exist = Role::where('role_for', $key)->first();
            if (!empty($is_role_exist)) {
                if ($is_role_exist->role_for == SuperAdminKey()) {
                    return $is_role_exist->id;
                }
            }
        }

        return false;
    }
}

if (!function_exists('prd')) {
    function prd($data)
    {
        echo "<pre>";
        print_r($data);
        die;
    }
}

if (!function_exists('pr')) {
    function pr($data)
    {
        echo "<pre>";
        print_r($data);
        die;
    }
}

if (!function_exists('UserDeviceDetails')) {
    function UserDeviceDetails()
    {
        // Temporarily simplified due to missing Agent package
        return 'Unknown Device';
    }
}

if (!function_exists('getSlug')) {
    function getSlug($table, $field_name, $string, $count = '')
    {
        $count = empty($count) ? 1 : $count + 1;
        $lower_case_string = strtolower($string);
        $space_remove_string = str_replace(" ", "-", $lower_case_string);
        $is_exist = DB::table($table)->where($field_name, $space_remove_string)->first();
        if (!empty($is_exist)) {
            return getSlug($table, $field_name, $space_remove_string . '-' . $count, $count);
        } else {
            return $space_remove_string;
        }
    }
}
