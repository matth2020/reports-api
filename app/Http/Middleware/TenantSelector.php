<?php

namespace App\Http\Middleware;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Closure;
use Config;
use DB;

class TenantSelector
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // if multi tenant and not in test, choose database depending on subdomain
        // or reject
        if (Config::get('tenancy.multiTenant') && env('APP_ENV') != 'testing') {
            $urlParams = explode(".", $_SERVER['HTTP_HOST']);

            // length 2 or 3 to support subdomain.host.com or just subdomain.host
            if ((count($urlParams) == 3 || count($urlParams) == 2) && !is_numeric($urlParams[0])) {
                $this->findAndConnectTenant($urlParams[0]);
            } else {
                return Response('Not allowed', 401);
            }
        }

        return $next($request);
    }

    private function findAndConnectTenant($subdomain)
    {
        //query connection details from account table
        Config::set(['database.connections.'.$subdomain =>
        [
            'driver' => 'mysql',
            'host' => Config::get('tenancy.host'),
            'port' => Config::get('tenancy.port'),
            'database' => $subdomain,
            'username' => Config::get('tenancy.username'),
            'password' => Config::get('tenancy.password'),
            'unix_socket' => '',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]]);
        // purge the connection so the new settings take effect
        DB::purge($subdomain);
        // select the connection
        DB::setDefaultConnection($subdomain);
        return;
    }
}
