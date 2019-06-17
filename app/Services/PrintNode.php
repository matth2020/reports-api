<?php
namespace App\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Printer;
use GuzzleHttp\Client;
use Config;

class PrintNode
{
    public static function createJob($Print, $title = '', $options = null)
    {
        // get the api key
        $Key = Config::get('services.printNode.key');
        $AppUrl = Config::get('app.url');

        $url = 'https://api.printnode.com/printjobs';

        $PrintJob = app()->make('stdClass');
        $PrintJob->printerId = (int)$Print->printer->external_id;
        $PrintJob->title = $title;
        $PrintJob->contentType = 'pdf_uri';
        $PrintJob->content = $AppUrl .'/print_que'.$Print->print_queue_id.'/print_node';
        $PrintJob->expireAfter = 3500; //1 hour seconds (100 seconds margin)
        $PrintJob->qty = $Print->copies;
        $PrintJob->source = 'XtractSolutionsAPI';

        $credentials = app()->make('stdClass');
        $credentials->user = self::randomString(20);
        $credentials->pass = self::randomString(20);

        // update print with hashed username/password
        $Print->auth_id = password_hash($credentials->user, PASSWORD_DEFAULT);
        $Print->auth_key = password_hash($credentials->pass, PASSWORD_DEFAULT);
        $Print->save();
        
        $authentication = app()->make('stdClass');
        $authentication->type = "BasicAuth";
        $authentication->credentials = $credentials;

        $PrintJob->authentication = $authentication;


        if (!is_null($options)) {
            $PrintJob->options = $options;
        }
        
        $Client = new Client();
        $res = $Client->request('POST', $url, ['verify' => [base_path('resources/assets/').'cacert.pem'], 'auth' => [$Key,null], 'body' => json_encode($PrintJob)]);
        if ($res->getStatusCode() != 201) {
            $data = json_encode(["errors" => [$res->getReasonPhrase()]]);
            $Print->status = 'error';
            $Print->save();
            // maybe record to xis log here?
        } else {
            $Print->status = 'job_id_'.trim($res->getBody());
            $Print->save();
        }
    }


    public static function refreshPrinters()
    {
        $res = PrintNode::callPrinter('/printers');
        // if status isn't 200... we will continue on and hope our
        // database is accurate.
        if ($res->getStatusCode() === 200) {
            $data = json_decode($res->getBody()->getContents());
            
            // make sure all of the printers are added to the database.
            $ids = [];
            foreach ($data as $printer) {
                try {
                    $PrinterRow = Printer::where('external_id', $printer->id)->firstOrFail();
                } catch (ModelNotFoundException $e) {
                    $PrinterRow = new Printer();
                    $PrinterRow->type = 'printNode';
                    $PrinterRow->name = $printer->name;
                    $PrinterRow->external_id = $printer->id;
                    $PrinterRow->save();
                }
                array_push($ids, $PrinterRow->external_id);
            }
            // next query the database (only printNode printers) and remove
            // any that printNode didn't know about.
            Printer::where('type', 'printNode')->whereNotIn('external_id', $ids)->delete();
            return true;
        }
        return false;
    }

    public static function getJobStatus($JobId)
    {
        \Log::info('checking job:'.$JobId);
        $res = self::callPrinter('/printjobs/'.$JobId.'/states');
        if ($res->getStatusCode() === 200) {
            return json_decode($res->getBody()->getContents())[0][0];
        } else {
            return null;
        }
    }

    private static function randomString($length = 8)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&';
        $randChars = [];
        $possibleChars = strlen($chars) - 1;
        for ($i = 0; $i < $length; $i++) {
            $n = rand(0, $possibleChars);
            $randChars[] = $chars[$n];
        }
        return implode($randChars);
    }

    private static function callPrinter($url, $callType = 'GET')
    {
        // get the api key
        $Key = Config::get('services.printNode.key');

        // make sure there is a leading / but just one
        $url = str_replace('//', '/', '/'.$url);
        $base_url = 'https://api.printnode.com';
        // in case they pre added the base_url
        $url = str_replace($base_url, '', $url);
        $url = $base_url . $url;
        
        $Client = new Client();
        return $Client->request(
            $callType,
            $url,
            [
                'verify' => [base_path('resources/assets/').'cacert.pem'],
                'auth' => [$Key,null]
            ]
        );
    }
}
