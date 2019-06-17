<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Panel;
use App\Models\PanelAntigen;
use DB;

class PanelController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    *
    * @return \Illuminate\Http\JsonResponse,
    * @SWG\Get(
    *     tags={"Panel"},
    *     path="/panel",
    *     summary="Returns a list of all panels in the system.",
    *     description="",
    *     operationId="api.panel.index",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="Panel object fields to return.",
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
        return $this::handleRequest($request, new Panel);
    }

    /**
    * Display a specific object from the resource.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     tags={"Panel"},
    *     path="/panel/{id}",
    *     summary="Returns a single panel in the system identified by {id}.",
    *     description="",
    *     operationId="api.panel.index.id",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="id",
    *        in="path",
    *        description="Id of the panel to return.",
    *        required=true,
    *        type="integer",
    *     ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="Panel object fields to return.",
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
    public function getPanel(request $request)
    {
        return $this::handleRequest($request, new Panel);
    }

    /**
     * Display a listing of the resource matching search criterion.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"Panel"},
     *     path="/panel/_search",
     *     summary="Returns a list of panels in the system matching the requested fields.",
     *     description="% may be used as a wild card.",
     *     operationId="api.panel.searchPanel",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *        name="panel object",
     *        in="body",
     *        description="Panel object containing only the properties to be searched by. (Any additional fields will be ignored. % may be used as a wild card)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/Panel"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Panel object fields to return.",
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
    public function searchPanel(request $request)
    {
        return $this::handleRequest($request, new Panel);
    }

    /**
    * Create an object.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Post(
    *     tags={"Panel"},
    *     path="/panel",
    *     summary="Create a new panel.",
    *     description="",
    *     operationId="api.panel.createPanel",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="body",
    *        in="body",
    *        description="Panel object to be created in the system. (The panel_id property will be automatically generated and will be ignored if present in the object)",
    *        required=true,
    *        @SWG\Schema(ref="#/definitions/Panel"),
    *     ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="Panel object fields to return",
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
    public function createPanel(Request $request)
    {
        return $this::handleRequest($request, new Panel);
    }

    /**
    * Delete an object.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Delete(
    *     tags={"Panel"},
    *     path="/panel/{id}",
    *     summary="Mark a panel as deleted.",
    *     description="",
    *     operationId="api.panel.deletePanel",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="id",
    *        in="path",
    *        description="Id of the panel to mark deleted.",
    *        required=true,
    *        type="integer",
    *      ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="Panel object fields to return.",
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
    public function deletePanel(request $request)
    {
        return $this::handleRequest($request, new Panel);
    }

    /**
    * Update an object.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Put(
    *     tags={"Panel"},
    *     path="/panel/{id}",
    *     summary="Update a panel object.",
    *     description="",
    *     operationId="api.panel.updatePanel",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *        name="id",
    *        in="path",
    *        description="Id of the panel to update.",
    *        required=true,
    *        type="integer",
    *     ),
    *     @SWG\Parameter(
    *        name="panel object",
    *        in="body",
    *        description="Panel object containing only the fields that need to be updated. (The panel_id property cannot be updated and will be ignored)",
    *        required=true,
    *        @SWG\Schema(ref="#/definitions/Panel"),
    *     ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="Panel object fields to return.",
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
    public function updatePanel(Request $request)
    {
        return $this::handleRequest($request, new Panel);
    }

    protected function queryWith($Query)
    {
        return $Query->with([
            'antigens'
        ]);
    }

    protected function deleteFromRequest($Object)
    {
        try {
            $Query = $this->queryWith($Object);
            $Query = $this->queryWhere($Query);
            $Object = $Query->findOrFail($this->RequestOptions->id);

            // delete the panel_antigen assignments first
            PanelAntigen::where('panel_id', $Object->panel_id)->delete();

            // now delete the panel as normal
            $Object = $Object->markDeleted($this->RequestOptions);
            return $this->finishAndFilter($Object);
        } catch (ModelNotFoundException $e) {
            return response()->json('Resource could not be located.', 404);
        }
    }

    protected function fixCreatedUpdatedInfo(request $request)
    {
        //this is also a convenient place to ensure no created_at/updated_at info makes it in to the db
        //from the user input.
        $requestData = $request->except(['created_at','updated_at','created_by','updated_by']);
        $request->replace($requestData);
        //Now lets explicitly set the correct values in case the model supports these.
        $Now = \DB::select('select now()')[0]->{'now()'};
        if ($this->RequestOptions->isCreate) {
            // this shouldn't be needed but even if the db isn't setup to set
            // the time this ensures it will happen
            $request->merge(['updated_at' => $Now]);
        } else {
            //Here we need to set updated_at and if possible, updated by
            $request->merge(['updated_at' => $Now]);
            if (!is_null($request->user())) {
                $request->merge(['updated_by' => $request->user()->user_id]);
            }
        }
        return $request;
    }

    protected function saveAndQuery(request $request, $Object)
    {
        // Convert the property names to match db and save
        $Object = $this::APItoDB($request, $Object);
        $query = $Object->save();

        $Antigens = $request->input('antigens');

        if (is_array($Antigens)) {
            // delete the previous panel_antigens
            PanelAntigen::where('panel_id', $Object->panel_id)->delete();

            foreach ($Antigens as $Antigen) {
                $NewPanelAntigen = new PanelAntigen();
                $NewPanelAntigen->antigen_id = $Antigen['antigen_id'];
                $NewPanelAntigen->panel_id = $Object->panel_id;
                $NewPanelAntigen->save();
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
}
