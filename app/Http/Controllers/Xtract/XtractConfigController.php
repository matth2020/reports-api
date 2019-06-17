<?php

namespace App\Http\Controllers\Xtract;

use App\Http\Controllers\Provider\ProviderController;
use App\Http\Controllers\Provider\ProfileController;
use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\Config\ConfigController;
use App\Http\Controllers\Status\StatusController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class XtractConfigController extends Controller
{
    public function globalAppConfig()
    {
        $configData = [];
        $data = $this::localXtractCall('Config', null, 'search', [
            'name' => 'box%'
        ]);
        $configData = array_merge($configData, $data);

        $data = $this::localXtractCall('Config', null, 'search', [
            'name' => 'priority_names'
        ]);
        $configData = array_merge($configData, $data);

        $data = $this::localXtractCall('Config', null, 'search', [
            'section' => 'dashboard'
        ]);
        $configData = array_merge($configData, $data);

        $data = $this::localXtractCall('Config', null, 'search', [
            'section' => 'patientDisplay'
        ]);
        $configData = array_merge($configData, $data);

        $data = $this::localXtractCall('Config', null, 'search', [
            'section' => 'adminDisplay'
        ]);
        $configData = array_merge($configData, $data);

        $data = $this::localXtractCall('Config', null, 'search', [
            'section' => 'safety'
        ]);
        $configData = array_merge($configData, $data);

        $data = $this::localXtractCall('Config', null, 'search', [
            'section' => 'read_only'
        ]);
        $configData = array_merge($configData, $data);

        $providerData = $this::localXtractCall('Provider');

        $profileData = $this::localXtractCall('Profile');

        return response()->json(['config_search' => $configData, 'provider_read_all' => $providerData, 'profile_read_all' => $profileData]);
    }

    public function nonXisInjectionsWindow(request $request, $patient_id)
    {
        $User = $this::localXtractCall('User');
        $Config = $this::localXtractCall('Config', null, 'search', [
            'name' => 'reaction_names'
        ]);
        $Prescription = $this::localXtractCall('Prescription', $patient_id);
        return response()->json(['user_read_all' => $User, 'patient_prescription_read_all' => $Prescription, 'config_search' => $Config]);
    }

    public function nonXpsPrescriptionsWindow()
    {
        $Clinic = $this::localXtractCall('Clinic');
        $Config = [];
        $data = $this::localXtractCall('Config', null, 'search', [
            'name' => 'sizes',
            'section' => 'prefs'
        ]);
        $Config = array_merge($Config, $data);
        $data = $this::localXtractCall('Config', null, 'search', [
            'section' => 'read_only'
        ]);
        $Config = array_merge($Config, $data);
        return response()->json(['clinic_read_all' => $Clinic, 'config_search' => $Config]);
    }

    public function loadPatientDisplay(request $request, $patient_id)
    {
        $Profile = $this::localXtractCall('Profile');
        // patient read happens as a separate call to spead loading of header
        // $Patient = $this::localXtractCall('Patient', $patient_id, 'read');
        $SetOrder = $this::localXtractCall('SetOrder', $patient_id);
        $Prescription = $this::localXtractCall('Prescription', $patient_id);
        $Encounter = $this::localXtractCall('Encounter', $patient_id, 'read');
        $result = [
            'profile_read_all' => $Profile,
            // 'patient_read' => $Patient,
            'patient_set_order_read_all' => $SetOrder,
            'patient_prescription_read_all' => $Prescription,
            'patient_encounter_read' => $Encounter
        ];

        $Treatment = $this->treatmentPlanAppConfig()->getData();
        foreach ($Treatment as $key => $value) {
            $result[$key] = $value;
        }
        $Global = $this->globalAppConfig()->getData();
        foreach ($Global as $key => $value) {
            $result[$key] = $value;
        }

        return response()->json($result);
    }
    public function loadInjectionData(request $request, $patient_id)
    {
        $Plan = $this::localXtractCall('InjectionPlan', $patient_id);
        $Adjust = $this::localXtractCall('InjectionAdjust', $patient_id);
        $Due = $this::localXtractCall('InjectionDue', $patient_id);
        return response()->json([
            'patient_injection_plan_read_all' => $Plan,
            'patient_injection_adjust_read_all' => $Adjust,
            'patient_injection_due_read_all' => $Due
        ]);
    }
    public function orderEntryWindow(request $request, $patient_id)
    {
        $SetOrder = $this::localXtractCall('SetOrder', $patient_id);
        $Skintest = $this::localXtractCall('Skintest', $patient_id);
        $Prescription = $this::localXtractCall('Prescription', $patient_id);
        $Extract = $this::localXtractCall('Extract');
        $Clinic = $this::localXtractCall('Clinic');
        $Config = [];
        $data = $this::localXtractCall('Config', null, 'search', [
            'name' => 'sizes',
            'section' => 'prefs'
        ]);
        $Config = array_merge($Config, $data);
        $data = $this::localXtractCall('Config', null, 'search', [
            'section' => 'read_only'
        ]);
        $Config = array_merge($Config, $data);
        return response()->json([
            'patient_set_order_read_all' => $SetOrder,
            'patient_skintest_read_all' => $Skintest,
            'patient_prescription_read_all' => $Prescription,
            'extract_read_all' => $Extract,
            'clinic_read_all' =>$Clinic,
            'config_search' => $Config
        ]);
    }

    public function immunotherapySummaryWindow(request $request, $patient_id)
    {
        $TrackingConfig = $this::localXtractCall('TrackingConfig', $patient_id);
        $TrackingValue = $this::localXtractCall('TrackingValue', $patient_id);
        $Injection = $this::localXtractCall('Injection', $patient_id);
        $Prescription = $this::localXtractCall('Prescription', $patient_id);
        $Config = $this::localXtractCall('Config', null, 'search', [
            'name' => 'reaction_names'
        ]);
        return response()->json([
            'patient_tracking_config_read_all' => $TrackingConfig,
            'patient_tracking_value_read_all' => $TrackingValue,
            'patient_injection_read_all' => $Injection,
            'patient_prescription_read_all' => $Prescription,
            'config_search' => $Config
        ]);
    }
    public function standardInjectionWindow(request $request, $patient_id)
    {
        $Prescription = $this::localXtractCall('Prescription', $patient_id);
        $Vial = $this::localXtractCall('Vial', $patient_id);
        $Due = $this::localXtractCall('InjectionDue', $patient_id);
        return response()->json([
            'patient_injection_due_read_all' => $Due,
            'patient_prescription_read_all' => $Prescription,
            'patient_vial_read_all' => $Vial,
        ]);
    }
    public function trackingWindow(request $request, $patient_id)
    {
        $TrackingConfig = $this::localXtractCall('TrackingConfig', $patient_id);
        $TrackingValue = $this::localXtractCall('TrackingValue', $patient_id);
        return response()->json([
            'patient_tracking_config_read_all' => $TrackingConfig,
            'patient_tracking_value_read_all' => $TrackingValue
        ]);
    }

    public function injAdjustWindow(request $request, $patient_id)
    {
        $Patient = $this::localXtractCall('Patient', $patient_id, 'read');
        $Prescription = $this::localXtractCall('Prescription', $patient_id);
        $Adjust = $this::localXtractCall('InjectionAdjust', $patient_id);
        return response()->json([
            'patient_prescription_read_all' => $Prescription,
            'patient_read' => $Patient,
            'patient_injection_adjust_read_all' => $Adjust
        ]);
    }

    public function patientDetailsWindow(request $request, $patient_id)
    {
        $Patient = $this::localXtractCall('Patient', $patient_id, 'read');
        $Enroll = $this::localXtractCall('Identification', $patient_id, 'read');
        $AssignedQuestionnaire = $this::localXtractCall('PatientQuestionnaire', $patient_id);
        $Questionnaire = $this::localXtractCall('Questionnaire');
        return response()->json([
            'patient_read' => $Patient,
            'patient_identification/enroll_read_all' => $Enroll,
            'patient_questionnaire_read_all' => $AssignedQuestionnaire,
            'questionnaire_read_all' => $Questionnaire
        ]);
    }

    public function globalAppConfigAuth()
    {
        $accountData = $this::localXtractCall('Account');
        $tsStatus = $this::localXtractCall('Status', null, 'search', ['type' => 'treatment_set']);
        $poStatus = $this::localXtractCall('Status', null, 'search', ['type' => 'purchase_order']);
        return response()->json(['account_read_all' => $accountData, 'treatment_set/status_read_all' => $tsStatus, 'purchase_order/status_read_all' => $poStatus]);
    }

    public function treatmentPlanAppConfig()
    {
        $tps = $this::localXtractCall('TreatmentPlan');
        $dps = $this::localXtractCall('DosingPlan');
        $codes = $this::localXtractCall('DosingPlan');
        $configData = [];
        $data = $this::localXtractCall('Config', null, 'search', [
            'name' => 'reaction_names'
        ]);
        $configData = array_merge($configData, $data);
        $data = $this::localXtractCall('Config', null, 'search', [
            'name' => 'box%'
        ]);
        $configData = array_merge($configData, $data);

        return response()->json([
            'dosing_plan_read_all' => $dps,
            'treatment_plan_read_all' => $tps,
            'config_search' => $configData
        ]);
    }

    protected static function localXtractCall($resource, $patient_id = null, $type = 'read_all', $payload = [])
    {
        $url = $resource;
        switch ($type) {
            case 'read':
                $reqMethod = 'GET';
                $controllerMethod = 'get'.$resource;
                break;
            case 'read_all':
                $reqMethod = 'GET';
                $controllerMethod = 'index';
                break;
            case 'search':
                $reqMethod = 'POST';
                $controllerMethod = 'search'.$resource;
                $url = $resource . '/_search';
                break;
            default:
                return null;
        }
        $ControllerClass = 'App\Http\Controllers\\'.$resource.'\\'.$resource.'Controller';
        switch ($resource) {
            case 'PatientQuestionnaire':
                $ControllerClass = 'App\Http\Controllers\Questionnaire\\'.$resource.'Controller';
                break;
            case 'Vial':
                $controllerMethod = 'getAllPatientVials';
                break;
            case 'TrackingConfig':
            case 'TrackingValue':
                $ControllerClass = 'App\Http\Controllers\Tracking\\'.$resource.'Controller';
                break;
            case 'Identification':
                $ControllerClass = 'App\Http\Controllers\Identification\IdentificationController';
                $url = 'identification/enroll';
                $controllerMethod = 'getEnrolledFingers';
                break;
            case 'InjectionDue':
            case 'InjectionAdjust':
            case 'InjectionPlan':
                $ControllerClass = 'App\Http\Controllers\Injection\\'.$resource.'Controller';
                break;
            case 'Patient':
                $url = ''; //patient prefix will be added below because of id
                break;
            case 'SetOrder':
                $ControllerClass = 'App\Http\Controllers\Order\SetOrderController';
                break;
            case 'Profile':
                $ControllerClass = 'App\Http\Controllers\Provider\ProfileController';
                break;
            case 'Status':
                $controllerMethod = 'searchAllStatus';
                break;
        }

        if (!is_null($patient_id)) {
            $url = '/patient/'.$patient_id.'/'.$url;
            $payload['patient_id'] = $patient_id;
        }

        $Controller = new $ControllerClass();
        $fakeRequest = Request::create('/v1/'.$url, $reqMethod, $payload);
        $data = new \Symfony\Component\HttpFoundation\ParameterBag;
        $data->add($payload);
        $fakeRequest->setJson($data);
        $result = $Controller->$controllerMethod($fakeRequest);

        return $result->getData();
    }
}
