<?php

namespace App\Models;

use App\Services\PrintNode;

class PrintQueue extends BaseModel
{
    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'print_queue';

    /**
     * The database primary_key used by the model.
     *
     * @var string
     */
    protected $primaryKey = 'print_queue_id';

    /**
     * Turn off timestamps.
     *
     * @var array
     */
    public $timestamps = false;

    protected $with = ['printer','createdBy'];

    protected $hidden = ['auth_id', 'auth_key', 'printer_id'];

    /**
     * Relationships
     */
    public function report()
    {
        return $this->belongsTo('App\Models\Report', 'reports_id');
    }

    public function printer()
    {
        return $this->belongsTo('App\Models\Printer', 'printer_id');
    }

    public function createdBy()
    {
        return $this->belongsTo('App\Models\User', 'created_by', 'user_id');
    }

    /**
     * Accessors
     */
    public function getFileAttribute()
    {
        $this->load('report');
        $this->file = $this->report->document;
        unset($this->report);
        return $this;
    }

    public function getExternalStatus()
    {
        if (strtoupper($this->printer->type) !== 'PRINTNODE') {
            $this->external_status = null;
        } else {
            // fetch status from printNode
            $printNodeId = str_replace('job_id_', '', $this->status);
            $this->external_status = PrintNode::getJobStatus($printNodeId);
        }
        return $this;
    }

    // public function getCreatedByAttribute()
    // {
    //     $this->load('user');
    //     $this->created_by = $this->user;
    //     unset($this->user);
    //     return $this;
    // }

    public static $DBtoRestConversion = [];

    protected function makeValidationRules($id, $data = null)
    {
        $Rules = [
            'printer_id' => ['integer', 'exists:printer,printer_id'],
            'reports_id' => ['integer', 'exists:reports,reports_id'],
            'processed_at' => ['notAllowed'], //auto set by db
            'status' => ['standard', 'between:0,32'],
            'auth_id' => ['notAllowed'], //auto set by api
            'auth_key' => ['notAllowed'], //auto set by api
            'created_at' => ['notAllowed'], //auto set by api
            'created_by' => ['notAllowed'], //auto set by api
        ];
        return $Rules;
    }

    protected function attachSometimesRules($Validator, $id)
    {
        $Validator->sometimes(['printer_id', 'reports_id'], 'required', function () use ($id) {
            return is_null($id);
        });

        return $Validator;
    }
}
