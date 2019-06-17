<?php

namespace App\Http\Middleware;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\ApiLog;
use Closure;

class ApiLogger
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
        //before handling the API request
        $ApiLog = new ApiLog();
        $Payload = array_filter($request->input());
        if (isset($Payload['password'])) {
            //if the payload contains a password redact it
            $Payload['password'] = '**redacted**';
        }
        $ApiLog->json_parameters = json_encode($Payload);
        $ApiLog->requester_ip = $request->ip();
        $ApiLog->method = $request->method();
        $ApiLog->path = $request->path();
        try {
            //get the id of the user if they are authorized
            $ApiLog->user_id = $request->user()['user_id'];
        } catch (NoActiveAccessTokenException $e) {
            // no logged in user
            $ApiLog->user_id = null;
        }

        try {
            $ApiLog->save();
        } catch (QueryException $e) {
            //There is a good chance the URL was to long for the path column, try truncating it as a last effort
            $ApiLog->path = substr($ApiLog->path, 0, 254);
            try {
                $ApiLog->save();
            } catch (QueryException $e) {
                if (
                    // these are all exceptions that generally mean the api
                    // couldn't contact mysql. During dev this likely mans
                    // the api machine went to sleep. In production... we
                    // haven't seen it, but returning 503 (service unavailable)
                    // is a reasonable thing to do.
                    preg_match('/PDOException: SQLSTATE\[HY000\] \[2002\] Network is down/', $e) ||
                    preg_match('/PDOException: SQLSTATE\[HY000\] \[2002\] Host is down/', $e) ||
                    preg_match('/SQLSTATE\[HY000\] \[2002\] Network is unreachable/', $e) ||
                    preg_match('/PDOException: SQLSTATE\[HY000\] \[2002\] Operation timed out/', $e)
                    ) {
                    \Log::info('Api logger middleware unable to contact database');
                    return response()->json('API unable to contact database.', 503);
                }
                //it still failed so handle it without crashing.
                Log::info('ApiLogger middleware exception:');
                Log::info($e);
                return $next($request);
            }
        }

        //handle the API request
        $response = $next($request);


        try {
            //After handling the API request, update the response code
            $ApiLog->response_code = $response->status();
            $ApiLog->save();
        } finally {
            return $response;
        }
    }
}
