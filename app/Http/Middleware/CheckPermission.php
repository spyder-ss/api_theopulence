<?php

namespace App\Http\Middleware;

use App\Helpers\Helper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    public function handle($request, Closure $next, $moduleName = '', $action = '')
    {
        $ADMIN_ROUTE_NAME = config('custom.ADMIN_ROUTE_NAME');

        if (!empty($moduleName) && !empty($action)) {
            if (!Helper::checkPermission($moduleName, $action)) {
                if ($action != "KeyStatistics") {
                    return redirect(url($ADMIN_ROUTE_NAME))->with('alert-danger', "You don't have permission to access this page");
                }
            }
        }

        return $next($request);
    }

}
