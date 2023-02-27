<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Module\Permission\Models\Permission;
use Module\Permission\Models\PermissionUser;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        $route = $request->route()->getName();


        if (auth()->id() != 1) {
            return $next($request);
        }

        if (auth()->user()->type != 'owner') {

            $permission = Permission::where('slug', $route)->first();

            if ($permission) {
                $permission_user = PermissionUser::where('permission_id', $permission->id)->where('user_id', auth()->id())->first();

                if (!$permission_user) {
                    redirect('/')->send();
                }
            } else {

                redirect('/')->send();
            }
        }

        return $next($request);
    }
}
