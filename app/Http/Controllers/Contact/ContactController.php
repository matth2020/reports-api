<?php

namespace App\Http\Controllers\Contact;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactLog;
use Twilio\Rest\Client;
use Mail;
use DB;

class ContactController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     tags={"Contact"},
    *     path="/patient/{patient_id}/contact",
    *     summary="Returns a list of all contacts history in the system.",
    *     description="",
    *     operationId="api.contact.index",
    *     produces={
    *        "application/json"
    *     },
    *     consumes={
    *        "application/json"
    *     },
    *     @SWG\Parameter(
    *         name="patient_id",
    *         in="path",
    *         description="The id of the patient whose contact logs are to be viewed.",
    *         required=true,
    *         type="integer",
    *         format="int32"
    *      ),
    *     @SWG\Parameter(
    *        name="fields",
    *        in="query",
    *        description="Contact object fields to return.",
    *        required=false,
    *        type="string",
    *        collectionFormat="csv"
    *     ),
    *     @SWG\Response(
    *        response=200,
    *        description="Contact list.",
    *        @SWG\Schema(
    *           type="array",
    *           @SWG\Items(ref="#/definitions/Contact")
    *        ),
    *     ),
    *     @SWG\Response(
    *         response=400,
    *         description="Malformed request.",
    *     ),
    *     @SWG\Response(
    *         response=401,
    *         description="Unauthorized action.",
    *     ),
    *     @SWG\Response(
    *         response=404,
    *         description="Resource not found.",
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
        return $this::handleRequest($request, new ContactLog);
    }

    /**
     * Display a specific object from the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Get(
     *     tags={"Contact"},
     *     path="/patient/{patient_id}/contact/{id}",
     *     summary="Returns a single contact log item from the system identified by {id}.",
     *     description="",
     *     operationId="api.contact.index.id",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose contact logs are to be viewed.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="id",
     *        in="path",
     *        description="Id of the contact log item to return.",
     *        required=true,
     *        type="integer",
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Contact object fields to return.",
     *        required=false,
     *        type="string",
     *        collectionFormat="csv"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Contact object.",
     *         @SWG\Schema(ref="#/definitions/Contact"),
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Malformed request.",
     *     ),
     *     @SWG\Response(
     *         response=401,
     *         description="Unauthorized action.",
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Resource not found.",
     *     ),
     *     security={
     *        {
     *           "xtract_auth":{
     *           }
     *        }
     *     }
     * )
     */
    public function getContact(request $request)
    {
        return $this::handleRequest($request, new ContactLog);
    }

    /**
     * Display a listing of the resource matching search criterion.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"Contact"},
     *     path="/patient/{patient_id}/contact/_search",
     *     summary="Returns a list contact history from the system matching the requested fields.",
     *     description="% may be used as a wild card.",
     *     operationId="api.contact.searchContact",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose contact logs are to be viewed.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="contact object",
     *        in="body",
     *        description="Contact object containing only the properties to be searched by. (Any additional fields will be ignored. % may be used as a wild card)",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/Contact"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Contact object fields to return.",
     *        required=false,
     *        type="string",
     *        collectionFormat="csv"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *        description="Contact list.",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Contact")
     *         ),
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Malformed request.",
     *     ),
     *     @SWG\Response(
     *         response=401,
     *         description="Unauthorized action.",
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Resource not found.",
     *     ),
     *     security={
     *        {
     *           "xtract_auth":{
     *           }
     *        }
     *     }
     * )
     */
    public function searchContact(request $request)
    {
        return $this::handleRequest($request, new ContactLog);
    }

    /**
     * Create an object.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Post(
     *     tags={"Contact"},
     *     path="/patient/{patient_id}/contact",
     *     summary="Create a new contact.",
     *     description="",
     *     operationId="api.contact.createContact",
     *     produces={
     *        "application/json"
     *     },
     *     consumes={
     *        "application/json"
     *     },
     *     @SWG\Parameter(
     *         name="patient_id",
     *         in="path",
     *         description="The id of the patient whose contact logs are to be viewed.",
     *         required=true,
     *         type="integer",
     *         format="int32"
     *      ),
     *     @SWG\Parameter(
     *        name="body",
     *        in="body",
     *        description="Object describing the message(s) to send. This WILL result in an email,sms,or both being sent.",
     *        required=true,
     *        @SWG\Schema(ref="#/definitions/Contact"),
     *     ),
     *     @SWG\Parameter(
     *        name="fields",
     *        in="query",
     *        description="Contact object fields to return.",
     *        required=false,
     *        type="array",
     *        @SWG\Items(type="string"),
     *        collectionFormat="csv",
     *     ),
     *     @SWG\Response(
     *        response=200,
     *        description="Contact object that was created.",
     *        @SWG\Schema(ref="#/definitions/Contact"),
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Malformed request.",
     *     ),
     *     @SWG\Response(
     *         response=401,
     *         description="Unauthorized action.",
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Resource not found.",
     *     ),
     *     security={
     *        {
     *           "xtract_auth":{
     *           }
     *        }
     *     }
     * )
     */
    public function createContact(Request $request)
    {
//         $Init = $this->init($request, $patient_id);

//         $ID = $patient_id;
//         $Content = $request->input('content');
//         $Subject = $request->input('subject');
//         $Method = $request->input('method');

//         $Error = $this->validateContact($request, $patient_id);
//         if($Errors){
//             return response()->json($Errors);
//         }

        // //I combined validation and error handling here which makes this a bit
        // //of a mess. I should separate this at some point.
//         if (gettype($Result) != 'boolean') {
//            //if result returned errors print them
//             return $Result;
//         } else {

//             $Messages = array();
//             //create logs
//             $Message = new Message();
//             $Message->content = $Content;
//             $Message->subject = $Subject;
//             $Message->deleted = 'T';
//             $Message->singleSend = 'T';
//             $Message->patientSelect = 'F';

//             $ContactLog = new ContactLog();
//             $ContactLog->patient_id = (int) $ID;

//             if (strtoupper($Method) === 'SMS') {
//                 if (in_array('SMS', $prefs)) {
//                     $ContactLog->contactBy = 'F,T,F';
//                     //safe the log rows to the db... if successful, send the message
//                     //else throw an error.
//                     $ContactLog = $this->saveContactLogs($ContactLog, $Message, $Init->filter);
//                     if ($ContactLog) {
//                         try {
//                             $this->sendSMS($Patient, $Content);
//                         } catch (Exception $e) {
//                             return response('An unknown error occurred. Exception:'.$e, 500);
//                         }
//                     } else {
//                         return response('Error, something went wrong. No messages were sent.', 500);
//                     }
//                 } else {
//                     $Messages['SMS'] = 'This user does not allow contact by SMS.';
//                 }
//             } elseif (strtoupper($Method) === 'EMAIL') {
//                 if (in_array('EMAIL', $prefs)) {
//                     $ContactLog->contactBy = 'T,F,F';
//                     //safe the log rows to the db... if successful, send the message
//                     //else throw an error.
//                     $ContactLog = $this->saveContactLogs($ContactLog, $Message, $Init->filter);
//                     if ($ContactLog) {
//                         try {
//                             $this->sendEmail($Patient, $Content, $Subject);
//                         } catch (Exception $e) {
//                             return response('An unknown error occurred. Exception:' + $e, 500);
//                         }
//                     } else {
//                         return response('Error, something went wrong. No messages were sent.', 500);
//                     }
//                 } else {
//                     $Messages['Email'] = 'This user does not allow contact by Email.';
//                 }
//             }
        // //
        // //add ability to contact by BOTH here
        // //
        // //
        // //

//             $Error['error'] = 'validation';
//             $Error['messages'] = $Messages;

//             if (count($Messages) > 0) {
//                 return response()->json($Error, 400);
//             } else {
//                 return response()->json($ContactLog);
//             }
//         }
        return response()->json('dissabled in code');
    }

    protected function queryWith($Query)
    {
        return $Query->with('message');
    }

    //No route exists for this.
    private function deleteContact(request $request)
    {
        return response()->json('Error, messages cannot be un-sent and history of contact cannot be deleted.', 404);
    }

    //No route exists for this.
    private function updateContact(Request $request)
    {
        return response()->json('Messages cannot be edited after being sent and contact history cannot be changed.', 404);
    }

    /**
     * called by the finalize customCollection method on each element of a collection
     *
     * @param  Config $Contact object returned from the database
     * @param         $Filter  Array of properties the response objects should include
     * @return Contact object
     */
    // protected function finalize($ObjectContactLog $Contact, $Filter){
    //     $Message = $Contact->message;
    //     unset($Contact->message);
    //     $Contact->subject = $Message->subject;
    //     $Contact->message = $Message->content;
    //     return $Contact;
    // }

    /**
     * Send email to specified patient
     * @param  patient $patient patient object to be contacted
     * @param  string  $message message to send
     * @param  string  $subject subject of the email
     * @return bool             true for success
     */
    private function sendEmail(patient $patient, string $message, string $subject)
    {
        Mail::send(
            'email.generic',
            array('firstname' => $patient->firstname, 'content' => $message),
            function ($message) use ($patient, $subject) {
                $message->to($patient->email, $patient->firstname.' '.$patient->lastname)->subject($subject);
            }
        );
        return true;
    }

    /**
     * Send SMS message to patient
     * @param  patient $patient patient object to contact
     * @param  string  $message message to send
     * @return bool             true for success
     */
    private function sendSMS(patient $patient, string $message)
    {
        $config = app()->make('config');
        $number = $config->get('app.twilio_phone');
        $token = $config->get('app.twilio_token');
        $sid = $config->get('app.twilio_ssid');
        $client = new Client($sid, $token);

        $client->messages->create(
            // the number you'd like to send the message to
            $patient->smsphone,
            array(
                // A Twilio phone number you purchased at twilio.com/console
                'from' => $number,
                // the body of the text message you'd like to send
                'body' => $message,
            )
        );
        return true;
    }

    /**
     * Save a contactlog and message
     * @param  ContactLog $ContactLog ContactLog object to be saved
     * @param  Message    $Message    Message object to be saved
     * @return object                 Contact object to return to API.
     */
    // private function saveContactLogs(ContactLog $ContactLog, Message $Message, $Filter)
    // {
    //     //start a database transaction to ensure both rows save
    //     try {
    //         DB::transaction(function () use ($ContactLog, $Message) {
    //             $Message->save();
    //             $ContactLog->message_id = $Message->message_id;
    //             $ContactLog->save();
    //         });
    //     } catch (Exception $e) {
    //         return false;
    //     }

    //     $Contact = ContactLog::find($ContactLog->contact_log_id)
    //         ->finalize($this, $Filter);

    //     return $Contact;
    // }

    protected function querySearchModifier($Query, $request = null)
    {
        //create a message search object and query for it
        $MessageSearch = $this->makeSearchMessage($request);
        return $Query->whereHas('message', function ($query) use ($MessageSearch) {
            $query->search($MessageSearch);
        });
    }

    /**
     * Because a contact as defined by the API doesn't exist in the DB, the search
     * object must be manually built by this function rather than using APItoDB
     * the way other controllers do.
     * @param  request $request the API request object
     * @return contactlog       a contact log object with the properties to search
     */
    protected function generateSearchObject($request, $Object)
    {
        $ContactSearch = new ContactLog();

        if ($request->json('patient_id') !== null) {
            $ContactSearch->patient_id = $request->json('patient_id');
        }

        if ($request->json('sent_timestamp') !== null) {
            $ContactSearch->sentTime = $request->json('sent_timestamp');
        }

        if ($request->json('method') !== null) {
            $ContactSearch->contactBy = strtoupper($request->json('method')) === strtoupper('SMS') ? 'F,T,F' : 'T,F,F';
        }
        return $ContactSearch;
    }

    /**
     * Because a message as defined by the API doesn't exist in the DB, the search
     * object must be manually built by this function rather than using APItoDB
     * the way other controllers do.
     * @param  request $request the API request object
     * @return message       a message object with the properties to search
     */
    private function makeSearchMessage(request $request)
    {
        $MessageSearch = new Message();

        if ($request->json('content') !== null) {
            $MessageSearch->content = $request->json('content');
        }

        if ($request->json('subject') !== null) {
            $MessageSearch->subject = $request->json('subject');
        }

        $MessageSearch->deleted = '%';

        return $MessageSearch;
    }
}
