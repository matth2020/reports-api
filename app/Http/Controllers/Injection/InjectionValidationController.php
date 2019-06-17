<?php

namespace App\Http\Controllers\Injection;

use App\Http\controllers\Injection\InjectionAdjustController;
use App\Http\Controllers\Injection\InjectionBaseController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Validator;
use App\Models\TreatPlanDetails;
use Illuminate\Http\Request;
use App\Models\Prescription;
use App\Models\Injection;
use App\Models\InjAdjust;
use App\Models\Compound;
use Carbon\Carbon;
use DB;

class InjectionValidationController extends InjectionBaseController
{
    /**
     * Create an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"Injection"},
     *     path="/patient/{patient_id}/injection/validate",
     *     summary="Validate a new injection.",
     *     description="",
     *     operationId="api.injection.validateInjection",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose injection is to be administered.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="body",
     *        in="body",
     *        description="Injection object to be validated. (The injection_id property will be automatically generated and will be ignored if present in the object)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/Injection"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Injection object fields to return",
     *        required=false,
     *        type="string",
     *        collectionFormat="csv"
     *     ),
    *     @SWG\Response(
    *        response=200,
    *        description="Successful call.",
    *        @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend200")
    *        ),
    *     ),
    *     @SWG\Response(
    *         response=400,
    *         description="Malformed request.",
    *         @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend400")
    *         ),
    *     ),
    *     @SWG\Response(
    *         response=401,
    *         description="Unauthorized action.",
    *         @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend401")
    *         ),
    *     ),
    *     @SWG\Response(
    *         response=404,
    *         description="Resource could not be located.",
    *         @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend404")
    *         ),
    *     ),
    *     @SWG\Response(
    *         response=500,
    *         description="Server error.",
    *         @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend500")
    *         ),
    *     ),
    *     security={
    *        {
    *           "xtract_auth":{
    *           }
    *        }
    *     }
    * )
    */
    public function validateInjection(Request $request, $patient_id, $user_id = null)
    {
        $this->getRequestOptions($request);
        $this->RequestOptions->user_id = is_null($user_id) ? $request->user()->user_id : $user_id;
        $timestamp = !is_null($request->json('datetime_administered')) ? $request->json('datetime_administered') : Carbon::now()->toDateTimeString();
        $request->merge([
            'patient_id' => $patient_id,
            'user_id' => is_null($user_id) ? $request->user()->user_id : $user_id,
            'datetime_administered' => $timestamp,
            'datetime_entered' => Carbon::now()->toDateTimeString()
        ]); //make patient_id available to the validator
        //If no reaction data was provided, we cant just use database defaults because the "no reaction" values
        //can be different between customers so we need to find out what they are and explicitly set them.
        $Reactions = $this::getReactionNames();

        if (is_null($request->input('local_reaction'))) {
            $request->merge(['local_reaction' => $Reactions->local[0]]);
        }
        if (is_null($request->input('systemic_reaction'))) {
            $request->merge(['systemic_reaction' => $Reactions->systemic[0]]);
        }
        return $this->handleRequest($request, new Injection);
    }

    /**
     * Create an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"Injection"},
     *     path="/patient/{patient_id}/multiinjection/validate",
     *     summary="Validate multiple injections in a single transaction.",
     *     description="",
     *     operationId="api.injection.validateMultiInjection",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose injection is to be validated.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="body",
     *        in="body",
     *        description="Injection object to be validated. (The injection_id property will be automatically generated and will be ignored if present in the object)",
     *        required=true,
     *        @SWG\Schema(
     *            type="array",
     *            @SWG\Items(ref="#/definitions/Injection")
     *        ),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Injection object fields to return",
     *        required=false,
     *        type="string",
     *        collectionFormat="csv"
     *     ),
    *     @SWG\Response(
    *        response=200,
    *        description="Successful call.",
    *        @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend200")
    *        ),
    *     ),
    *     @SWG\Response(
    *         response=400,
    *         description="Malformed request.",
    *         @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend400")
    *         ),
    *     ),
    *     @SWG\Response(
    *         response=401,
    *         description="Unauthorized action.",
    *         @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend401")
    *         ),
    *     ),
    *     @SWG\Response(
    *         response=404,
    *         description="Resource could not be located.",
    *         @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend404")
    *         ),
    *     ),
    *     @SWG\Response(
    *         response=500,
    *         description="Server error.",
    *         @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Jsend500")
    *         ),
    *     ),
    *     security={
    *        {
    *           "xtract_auth":{
    *           }
    *        }
    *     }
    * )
    */
    public function validateMultiInjection(Request $request, $patient_id)
    {
        $this::getRequestOptions($request);
        $this->RequestOptions->user_id = $request->user()->user_id;
        //Note that the code below looks a little generic because I have started a first pass at
        //making something somewhat universal that eventually can be moved to controller for
        //standard array based create endpoints. AMH
        $Injection = new Injection; //just an instance to request the validator from
        $Errors = [];
        foreach ($request->all() as $key => $Object) {
            if ($key === 'transaction_id') {
                continue;
            }
            $timestamp = isset($Object['datetime_administered']) ? $Object['datetime_administered'] : Carbon::now()->toDateTimeString();
            //this is equivalent of the array merge that happens in the create endpoint but it must happen
            //here in order to be ready for validation.
            $Object['patient_id'] = $patient_id;
            $Object['user_id'] = $this->RequestOptions->user_id;
            $Object['datetime_administered'] = $timestamp;
            $Object['datetime_entered'] = Carbon::now()->toDateTimeString();
            $id = isset($Object[$Injection->getKeyName()]) ? $Object[$Injection->getKeyName()] : null;
            if (!$Injection->Validate($Object, $id)) {
                $Errors ['injection '.$key] = $Injection->errors();
            }
        }

        if (sizeof($Errors) > 0) {
            return response()->json($Errors, 400);
        }
        $Results = [];
        //no validation errors so now start a transaction and loop through single creates.
        DB::transaction(function () use ($request, $patient_id, $Results) {
            foreach ($request->all() as $key => $Object) {
                if ($key === 'transaction_id') {
                    continue;
                }
                $fakeRequest = Request::create('/v1/patient/' . $patient_id . '/injection', 'POST', $Object);
                $data = new \Symfony\Component\HttpFoundation\ParameterBag;
                $data->add($Object);
                $fakeRequest->setJson($data);

                $result = $this->createInjection($fakeRequest, $patient_id, $request->user()->user_id);

                if ($result->status() != 200) {
                    throw new Exception(); //if one of the injections doesnt record, throw an exception to
                    //hopefully break the transaction? This needs testing.
                }

                array_push($Results, $result->getData());
            }
        });
        //return overall result array
        return response()->json($Results);
    }

    protected function saveAndQuery(request $request, $Object)
    {
        // No actual save takes place here, if no validation errors were found
        // we just return the object under validation
        return $this->finishAndFilter($request->input());
    }

    protected function finishAndFilter($Object)
    {
        return response()->json((object)$Object, 200);
    }
}
