<?php

namespace App\Http\Middleware;

use App\Models\ApiLog;
use Closure;
use Log;

class JsendFormatter
{
    private static function isJson($string)
    {
        return is_string($string) && (is_array(json_decode($string, true)) || $string === "");
    }

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
        // for all v1/ paths, require that PUT, POST, and DELETE carry valid JSON

        $isV1Api = strpos($request->path(), 'v1/') === 0;
        $isAuth = strpos($request->path(), 'v1/app-login') === 0;
        $isAuth = strpos($request->path(), 'v1/refresh-token') === 0 || $isAuth;
        $method = strtoupper($request->method());

        if ($isV1Api && !$isAuth && ($method == 'PUT' || $method == 'POST' || $method == 'DELETE')) {
            if (!$this::isJson($request->getContent())) {
                return response()->json([
                    'status' => 'validation',
                    'errors' => [
                        'json' => 'bad format'
                        ]
                ], 400);
            }
        }

        // process the request

        $response = $next($request);
        $Pagination = null;

        if ($response->headers->get('content-type') == "application/json") {
            try {
                $responseData = json_decode($response->content());
                if (isset($responseData->pagination)) {
                    $Pagination = $responseData->pagination;
                    $responseData = $responseData->data;
                }
                $statusCode = $response->status();
                $resource = $this::getResourceType($request);
                //Don't modify oath token responses as they are standard.
                if ($resource != "access-token") {
                    $response = $this::jsendResponse($responseData, $statusCode, $resource, $Pagination);
                }
            } catch (Exception $e) {
                // if there was an exception in formatting the response, skip formatting and just return it as is.
            }
        }

        return $response;
    }

    private static function jsendResponse($responseData, int $statusCode, string $resource, $pagination = null)
    {
        if ($statusCode == 200) {
            $status = "success";
            if (!is_null($resource)) {
                // Laravel passport is a little lame in its error
                // responses and only returns 200 status code so
                // we have to manually check for errors here.
                if ($resource === "app-login" && isset($responseData->error)) {
                    $status = 'fail';
                    $statusCode = 401;
                    $responseData = (object) array(
                        $resource => $responseData->message
                    );
                } else {
                    $responseData = (object) array(
                        $resource => $responseData
                    );
                }
            }
        } elseif ($statusCode == 500) {
            $status = "error";
        } elseif ($statusCode == 400) {
            $status = "validation";
        } else {
            $status = "fail";
        }

        $responseObj = app()->make('stdClass');
        $responseObj->status = $status;
        if ($statusCode == 200) {
            $responseObj->data = $responseData;
        } elseif ($statusCode == 400) {
            $responseObj = self::splitValidationMessages($responseObj, $responseData);
        } else {
            $responseObj->message = $responseData;
        }
        if (!is_null($pagination)) {
            $responseObj->pagination = $pagination;
        }

        return response()->json($responseObj, $statusCode);
    }

    private static function splitValidationMessages($ResponseObj, $Messages, $Errors = [], $ConfirmationRequired = [])
    {
        foreach ($Messages as $key => $Message) {
            if (is_object($Message)) {
                $TmpObj = app()->make('stdClass');
                $TmpObj = self::splitValidationMessages($TmpObj, $Message);
                if (isset($TmpObj->errors)) {
                    $Errors[$key] = $TmpObj->errors;
                }
                if (isset($TmpObj->confirmation_required)) {
                    $ConfirmationRequired[$key] = $TmpObj->confirmation_required;
                }
            } else {
                if (strpos(strtolower($key), 'override_') !== false) {
                    $ConfirmationRequired[$key] = $Message;
                } else {
                    $Errors[$key] = $Message;
                }
            }
        }
        if (sizeof($Errors) > 0) {
            $ResponseObj->errors = $Errors;
        }
        if (sizeof($ConfirmationRequired) > 0) {
            $ResponseObj->confirmation_required = $ConfirmationRequired;
        }

        return $ResponseObj;
    }

    public static function getResourceType($request)
    {
        $pathArray = explode('/', $request->path());
        if (preg_match('/.*patient\/([0-9]+\/)?waitlist\/[0-9]+\/link/', $request->path())) {
            $path = 'patient';
        } else {
            while (sizeOf($pathArray) > 0) {
                $path = array_pop($pathArray);
                if ($path != '_search' &&
                $path != 'limit' &&
                $path != 'offset' &&
                $path != 'mix' &&
                !is_numeric($path)
                ) {
                    break;
                } else {
                    $path = null;
                }
            }
        }
        return $path;
    }
}
