<?php
namespace App\BusinessLogic\Reports;

use Carbon\Carbon;
use App\Models\Answer;
use App\Models\Patient;
use App\Models\Injection;
use App\Models\TreatmentSet;
use App\Models\PurchaseOrder;
use App\Models\TrackingValue;

class ReportDataGenerator extends XtractReport
{
    private $Report;
    private $Now;

    public function __construct($Report)
    {
        $this->Report = $Report;
        $this->Now = Carbon::now()->toDateString();
    }

    public function generate()
    {
        $Report = $this->Report;
        $TemplateRow = $Report->template;
        $ReportInputData = json_decode($Report->xml);

        $ReportData = [
            'patient_id' => $Report->patient_id,
            'Generated_date' => $this->Now,
            'Report_date' => isset($ReportInputData->date) ? Carbon::createFromFormat('Y-m-d', $ReportInputData->date) : $this->Now
        ];
        /*$ar = $this->treatmentSetQuery($ReportData, $ReportInputData->treatment_set_id);
        echo dd($ar['TreatmentSet']['vials'][0]['vials'][0]['mix_date']);
        die();*/
        $Queries = explode(',', $TemplateRow->queriesCSV);
        foreach ($Queries as $Query) {
            switch (strtolower($Query)) {
                case 'patient':
                    $ReportData = $this->patientQuery($ReportData);
                    break;
                case 'dailyinjection':
                    $ReportData = $this->dailyInjectionQuery($ReportData);
                    break;
                case 'treatmentset':
                    $ReportData = $this->treatmentSetQuery($ReportData, $ReportInputData->treatment_set_id);
                    break;
                case 'purchaseorder':
                    $ReportData = $this->purchaseOrderQuery($ReportData, $QueryParams['purchase_order_id']);
                    break;
                case 'tracking':
                    $ReportData = $this->trackingQuery($ReportData);
                    break;
                case 'questionnaire':
                    $ReportData = $this->questionnaireQuery($ReportData);
                    break;
            }
        }
        return $ReportData;
    }

    private function patientQuery($ReportData)
    {
        $ReportData['Patient'] = Patient::find($ReportData['patient_id'])->toArray();
        return $ReportData;
    }

    private function purchaseOrderQuery($ReportData, $OrderId)
    {
        // do queries and collect data
        $Order = PurchaseOrder::with([
            'treatmentSets',
            'treatmentSets.prescription.provider',
            'treatmentSets.prescription.treatmentPlan',
            'treatmentSets.compounds.vials.user',
            'treatmentSets.compounds.vials.inventory',
            'treatmentSets.compounds.vials.inventory.extract',
            'treatmentSets.compounds.vials.dosing',
            'treatmentSets.compounds.vials.inventory.extract.unitType'
        ])
            ->find($OrderId);


        $ReportData['Order'] = $Order->toArray();
        return $ReportData;
    }

    private function treatmentSetQuery($ReportData, $SetId)
    {
        $TreatmentSet = TreatmentSet::with(['prescription.provider','prescription.treatmentPlan','compounds.vials.user','compounds.vials.inventory','compounds.vials.inventory.extract','compounds.vials.dosing','compounds.vials.inventory.extract.unitType'])
                ->where('patient_id', $ReportData['patient_id'])
                ->findOrFail($SetId);

        $ReportData['TreatmentSet'] = $TreatmentSet->toArray();

        return $ReportData;
    }

    private function questionnaireQuery($ReportData)
    {
        $QuestionnaireAnswers = Answer::where('patient_id', $ReportData['patient_id'])
            ->whereRaw('date(date) = "'. $ReportData['Report_date'] . '"')
            ->get();
        $ReportData['Answers'] = $QuestionnaireAnswers->toArray();
        return $ReportData;
    }

    private function trackingQuery($ReportData)
    {
        $Tracking = TrackingValue::where('patient_id', $ReportData['patient_id'])
            ->whereRaw('date(date) = "'. $ReportData['Report_date'] . '"')
            ->get();
        $ReportData['Tracking'] = $Tracking->toArray();
        return $ReportData;
    }

    private function dailyInjectionQuery($ReportData)
    {
        $Injections = Injection::whereHas('compound', function ($Query) use ($ReportData) {
            $Query->whereHas('prescription', function ($innerQuery) use ($ReportData) {
                $innerQuery->where('patient_id', $ReportData['patient_id']);
            });
        })->whereRaw('date(date) = "'. $ReportData['Report_date'] . '"')
        ->with('compound.prescription.provider', 'user')
        ->get();

        $ReportData['Injections'] = $Injections->toArray();
        return $ReportData;
    }
}
