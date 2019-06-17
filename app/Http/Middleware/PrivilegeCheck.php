<?php

namespace App\Http\Middleware;

use Closure;

class PrivilegeCheck
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next, $roles)
    {
        $config = app()->make('config');
        $enabled = $config->get('auth.devUserLevels') === true;
        $rolesArray = explode(',', $roles);
        if ($enabled) {
            $found = false;
            foreach ($rolesArray as $role) {
                if (!$request->user()->hasPrivilege($role)) {
                    continue;
                } else {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                return response()->json('privilege_error: User does not have the required '.$role.' privilege.', 401);
            }
        }

        return $next($request);
    }
}
