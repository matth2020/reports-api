<?php

namespace App\Http\Controllers\CompatibilityClass;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CompatibilityClass;
use App\Models\Extract;
use App\Models\Antigen;
use App\Models\ClassIncompatibility;
use DB;

class CompatibilityClassController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    *
    * @return \Illuminate\Http\JsonResponse,
    * @SWG\Get(
    *     tags={"Compatibility"},
    *     path="/compatibility_class",
    *     summary="Returns a list of all compatibilityclass in the system.",
    *     description="",
    *     operationId="api.compatibilityclass.index",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="CompatibilityClass object fields to return.",
    *        required=false,
    *        type="string",
    *        collectionFormat="csv"
    *     ),
    *     @SWG\Parameter(
    *        name="offset",
    *        in="query",
    *        description="Offset past first match. (Requires a limit value)",
    *        required=false,
    *        type="integer",
    *     ),
    *     @SWG\Parameter(
    *         name="limit",
    *         in="query",
    *         description="Maximum number of results to return.",
    *         required=false,
    *         type="integer",
    *         format="int32"
    *      ),
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
    public function index(request $request)
    {
        return $this::handleRequest($request, new CompatibilityClass);
    }

    /**
    * Display a specific object from the resource.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     tags={"Compatibility"},
    *     path="/compatibility_class/{id}",
    *     summary="Returns a single compatibilityclass in the system identified by {id}.",
    *     description="",
    *     operationId="api.compatibilityclass.index.id",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="id",
    *        in="path",
    *        description="Id of the compatibilityclass to return.",
    *        required=true,
    *        type="integer",
    *     ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="CompatibilityClass object fields to return.",
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
    public function getCompatibilityClass(request $request)
    {
        return $this::handleRequest($request, new CompatibilityClass);
    }

    /**
     * Display a listing of the resource matching search criterion.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"Compatibility"},
     *     path="/compatibility_class/_search",
     *     summary="Returns a list of compatibilityclasss in the system matching the requested fields.",
     *     description="% may be used as a wild card.",
     *     operationId="api.compatibilityclass.searchCompatibilityClass",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="compatibilityclass object",
     *        in="body",
     *        description="Compatibility Class object containing only the properties to be searched by. (Any additional fields will be ignored. % may be used as a wild card)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/CompatibilityClass"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="CompatibilityClass object fields to return.",
     *        required=false,
     *        type="string",
     *        collectionFormat="csv"
     *     ),
     *     @SWG\Parameter(
     *        name="offset",
     *        in="query",
     *        description="Offset past first match. (Requires a limit value)",
     *        required=false,
     *        type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Maximum number of results to return.",
     *         required=false,
     *         type="integer",
     *         format="int32"
     *      ),
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
    public function searchCompatibilityClass(request $request)
    {
        return $this::handleRequest($request, new CompatibilityClass);
    }

    /**
    * Create an object.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Post(
    *     tags={"Compatibility"},
    *     path="/compatibility_class",
    *     summary="Create a new compatibilityclass.",
    *     description="",
    *     operationId="api.compatibilityclass.createCompatibilityClass",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="body",
    *        in="body",
    *        description="CompatibilityClass object to be created in the system. (The compatibilityclass_id property will be automatically generated and will be ignored if present in the object)",
    *        required=true,
    *        @SWG\Schema(ref="#/definitions/CompatibilityClass"),
    *     ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="CompatibilityClass object fields to return",
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
    public function createCompatibilityClass(Request $request)
    {
        return $this::handleRequest($request, new CompatibilityClass);
    }

    /**
    * Delete an object.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Delete(
    *     tags={"Compatibility"},
    *     path="/compatibility_class/{id}",
    *     summary="Mark a compatibilityclass as deleted.",
    *     description="",
    *     operationId="api.compatibilityclass.deleteCompatibilityClass",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="id",
    *        in="path",
    *        description="Id of the compatibilityclass to mark deleted.",
    *        required=true,
    *        type="integer",
    *      ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="CompatibilityClass object fields to return.",
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
    public function deleteCompatibilityClass(request $request)
    {
        // return $this::handleRequest($request, new CompatibilityClass);
        $this::getRequestOptions($request);
        // find the class to delete
        try {
            $Class = CompatibilityClass::findOrFail($this->RequestOptions->id);
        } catch (ModelNotFoundException $e) {
            return response()->json('Resource could not be located.', 404);
        }
        DB::transaction(function () use ($Class) {
            // update antigens pointing to that class
            $AntigensToUpdate = Antigen::where('compatibility_class_id', $Class->compatibility_class_id)->get();
            foreach ($AntigensToUpdate as $Antigen) {
                $Antigen->compatibility_class_id = null;
                $Antigen->save();
            }
            // update extracts pointing to that class
            $ExtractsToUpdate = Extract::where('compatibility_class_id', $Class->compatibility_class_id)->get();
            foreach ($ExtractsToUpdate as $Extract) {
                $Extract->compatibility_class_id = null;
                $Extract->save();
            }
            // delete class_incomatibility rows
            $Incompatibilities = ClassIncompatibility::where('class_id_1', $Class->compatibility_class_id)->orWhere('class_id_2', $Class->compatibility_class_id)->get();
            foreach ($Incompatibilities as $Incompatibility) {
                $Incompatibility->delete();
            }
            // finally delete the class
            $Class->delete();
        });
        return response()->json($Class, 200);
    }

    /**
    * Update an object.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Put(
    *     tags={"Compatibility"},
    *     path="/compatibility_class/{id}",
    *     summary="Update a compatibilityclass object.",
    *     description="",
    *     operationId="api.compatibilityclass.updateCompatibilityClass",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="id",
    *        in="path",
    *        description="Id of the compatibilityclass to update.",
    *        required=true,
    *        type="integer",
    *     ),
    *     @SWG\Parameter(
    *        name="compatibilityclass object",
    *        in="body",
    *        description="CompatibilityClass object containing only the fields that need to be updated. (The compatibilityclass_id property cannot be updated and will be ignored)",
    *        required=true,
    *        @SWG\Schema(ref="#/definitions/CompatibilityClass"),
    *     ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="CompatibilityClass object fields to return.",
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
    public function updateCompatibilityClass(Request $request)
    {
        return $this::handleRequest($request, new CompatibilityClass);
    }

    protected function saveAndQuery(request $request, $Object)
    {
        // Convert the property names to match db and save
        $Object = $this::APItoDB($request, $Object);
        $query = $Object->save();

        $IncompatibleClasses = $request->input('incompatible_classes');

        if (is_array($IncompatibleClasses)) {
            // delete the previous compatibilities
            ClassIncompatibility::where('class_id_1', $Object->compatibility_class_id)
               ->orWhere('class_id_2', $Object->compatibility_class_id)->delete();

            foreach ($IncompatibleClasses as $Class) {
                $NewIncomatability = new ClassIncompatibility();
                $NewIncomatability->class_id_1 = $Class['compatibility_class_id'];
                $NewIncomatability->class_id_2 = $Object->compatibility_class_id;
                $NewIncomatability->save();
            }
        }

        //If we are saving something with a null id it must have been a create
        //so use the objects primary key to find the created id.
        $primaryId = is_null($this->RequestOptions->id) ? $Object[$Object->getKeyName()] : $this->RequestOptions->id;

        // Fetch the resulting object and return it
        $Query = App()->make(get_class($Object));
        $newObject = $this->queryWith($Query)->find($primaryId);
        return $this->finishAndFilter($newObject);
    }

    protected function finalize($Object)
    {
        $Object->incompatibleClasses();
        $Incompatibilities = $Object->incompatibleClasses;
        foreach ($Incompatibilities as $class) {
            unset($class->class_id_2);
            unset($class->class_id_1);
        }
        return $Object;
    }

    protected function queryWith($Query)
    {
        return $Query->with([
            'incompatibleClasses1','incompatibleClasses2'
        ]);
    }
}
