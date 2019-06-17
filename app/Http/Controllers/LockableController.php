<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Lock\LockController;
use App\Models\PatientConfig;
use App\Models\Padlock;
use Carbon\Carbon;

class LockableController extends Controller
{
    // this will be called during any create/update/delete to any lockable endpoint.
    protected function getLock()
    {
        $requiredLocks = get_class($this)::$requiredLocks;

        if (sizeOf($requiredLocks) === 0) {
            // no locks to acquire
            return true;
        }
        // get all non expired locks that have
        // names in the array of possible locks.
        $Locks = PatientConfig::whereDoesntHave('padlock', function ($Query) {
            return $Query->where('locked_until', '>', Carbon::now()->toDateTimeString());
        })
        ->where('name', 'lock')
        ->where('value', $requiredLocks)
        ->get();

        $LocksToCreate = [];
        $userOwnsAllExistingLocks = true;
        foreach ($requiredLocks as $Lock) {
            //see if each lock exists and belongs to the current user
            //if they belong to another user, we will return a 403 access denied
            //if the locks don't exist we will create them.
            if ($Locks->contains('value', $Lock)) {
                //lock exists, see if its this users
                if ($Locks->where('value', $Lock)->contains('created_by', $this->RequestOptions->user_id)) {
                    $userOwnsAllExistingLocks = $userOwnsAllExistingLocks && true;
                } else {
                    $userOwnsAllExistingLocks = $userOwnsAllExistingLocks && false;
                }
            } else {
                //we should create the lock
                array_push($LocksToCreate, $Lock);
            }
        }

        if ($userOwnsAllExistingLocks) {
            // get any additional required locks
            foreach ($LocksToCreate as $newLock) {
                // make the new lock.
                $LockController = new LockController();

                $Config = [
                    'patient_id' => $this->RequestOptions->patient_id,
                    'name' => 'lock',
                    'type' => $newLock
                ];

                $fakeRequest = \Request::create('/v1/patient/'.$this->RequestOptions->patient_id.'/lock', 'POST', $Config);
                $fakeRequest->user = $this->RequestOptions->user;
                $data = new \Symfony\Component\HttpFoundation\ParameterBag;
                $data->add($Config);
                $fakeRequest->setJson($data);
                $result = $LockController->createLock($fakeRequest, null, $this->RequestOptions);
            }
            return true;
        } else {
            return false;
        }
    }

    public static $requiredLocks = [];
}
