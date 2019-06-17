<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;

class ApiTransactionId
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
        $QueryParams = $request->query();

        //handle the API request
        $response = $next($request);

        //after the request
        if (isset($QueryParams['transaction_id'])) {
            $responseData = json_decode($response->content());
            if (!is_null($responseData)) {
                $statusCode = $response->status();
                $responseData->transaction_id = $QueryParams['transaction_id'];
                return response()->json($responseData, $statusCode);
            } else {
                return $response;
            }
        } else {
            return $response;
        }
    }
}
