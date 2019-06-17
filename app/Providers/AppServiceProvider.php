<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
    * Register any application services.
    *
    * @return void
    */
    public function register()
    {
        $this->app->singleton('mailer', function ($app) {
            $app->configure('services');
            return $app->loadComponent('mail', 'Illuminate\Mail\MailServiceProvider', 'mailer');
        });
    }
    public function boot()
    {
        Validator::extend('numericGte', 'App\Http\CustomValidator@validateGte');
        Validator::extend('numericLte', 'App\Http\CustomValidator@validateLte');
        Validator::extend('notAllowed', 'App\Http\CustomValidator@validateNotAllowed');
        Validator::extend('validVialBarcode', 'App\Http\CustomValidator@validateValidVialBarcode');
        Validator::extend('validPriority', 'App\Http\CustomValidator@validateValidPriority');
        Validator::extend('validPatientLock', 'App\Http\CustomValidator@validateValidPatientLock');
        Validator::extend('badAnswers', 'App\Http\CustomValidator@validateBadAnswers');
        Validator::extend('configMultiColumnKey', 'App\Http\CustomValidator@validateConfigMultiColumnKey');
        Validator::extend('treatmentPlanDosesIncrease', 'App\Http\CustomValidator@validateTreatmentPlanDosesIncrease');
        Validator::extend('treatmentPlanStepsZeroBased', 'App\Http\CustomValidator@validateTreatmentPlanStepsZeroBased');
        Validator::extend('treatmentPlanStepsIncrease', 'App\Http\CustomValidator@validateTreatmentPlanStepsIncrease');
        Validator::extend('validTpDetails', 'App\Http\CustomValidator@validateValidTpDetails');
        Validator::extend('validDosingAdjustments', 'App\Http\CustomValidator@validateValidDosingAdjustments');
        Validator::extend('validDosingSets', 'App\Http\CustomValidator@validateValidDosingSets');
        Validator::extend('hasAllReactionTypes', 'App\Http\CustomValidator@validateHasAllReactionTypes');
        Validator::extend('validInQuestionnaires', 'App\Http\CustomValidator@validateInQuestionnaires');
        Validator::extend('validMultiAnswer', 'App\Http\CustomValidator@validateValidMultiAnswer');
        Validator::extend('validQuestionAnswer', 'App\Http\CustomValidator@validateValidQuestionAnswer');
        Validator::extend('validFmd', 'App\Http\CustomValidator@validateFmd');
        Validator::extend('validQuestionType', 'App\Http\CustomValidator@validateValidQuestionType');
        Validator::extend('inReactions', 'App\Http\CustomValidator@validateInReactions');
        Validator::extend('phone', 'App\Http\CustomValidator@validatePhone');
        Validator::extend('dilution', 'App\Http\CustomValidator@validateDilution');
        Validator::extend('tpDilution', 'App\Http\CustomValidator@validateTpDilution');
        Validator::extend('tpDoseDilution', 'App\Http\CustomValidator@validateTpDoseDilution');
        Validator::extend('knownConfigKey', 'App\Http\CustomValidator@validateKnownConfigKey');
        Validator::extend('rxDilution', 'App\Http\CustomValidator@validateRxDilution');
        Validator::extend('distinctPatient', 'App\Http\CustomValidator@validateDistinctPatient');
        Validator::extend('decimal52', 'App\Http\CustomValidator@validateDecimal52');
        Validator::extend('decimal63', 'App\Http\CustomValidator@validateDecimal63');
        Validator::extend('decimal73', 'App\Http\CustomValidator@validateDecimal73');
        Validator::extend('standard', 'App\Http\CustomValidator@validateStandard');
        Validator::extend('change_reason', 'App\Http\CustomValidator@validateChangeReason');
        Validator::extend('season', 'App\Http\CustomValidator@validateSeason');
        Validator::extend('standard2', 'App\Http\CustomValidator@validateStandard2');
        Validator::extend('standard3', 'App\Http\CustomValidator@validateStandard3');
        Validator::extend('outdatesCSV', 'App\Http\CustomValidator@validateOutdatesCSV');
        Validator::extend('notes', 'App\Http\CustomValidator@validateNotes');
        Validator::extend('zipcode', 'App\Http\CustomValidator@validateZipcode');
        Validator::extend('inventoryInHistory', 'App\Http\CustomValidator@validateInventoryInHistory');
        Validator::extend('validSize', 'App\Http\CustomValidator@validateValidSize');
        Validator::extend('matchQueuedName', 'App\Http\CustomValidator@validateMatchQueuedName');
        Validator::extend('matchQueuedRx', 'App\Http\CustomValidator@validateMatchQueuedRx');
        Validator::extend('matchRxName', 'App\Http\CustomValidator@validateMatchRxName');
        Validator::extend('optins', 'App\Http\CustomValidator@validateOptins');
        Validator::extend('colorCSV', 'App\Http\CustomValidator@validateColorCSV');
        Validator::extend('extractCSV', 'App\Http\CustomValidator@validateExtractCSV');
        Validator::extend('color', 'App\Http\CustomValidator@validateColor');
        Validator::extend('PV1', 'App\Http\CustomValidator@validatePV1');
        Validator::extend('PID', 'App\Http\CustomValidator@validatePID');
    }
}
