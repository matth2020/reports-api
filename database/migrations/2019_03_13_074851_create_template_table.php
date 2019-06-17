<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Template;

class CreateTemplateTable extends Migration
{
    /**
     * default template declarations
     */
    public static $defaultInjectionTemplate =
    '{
        "file_info": {
            "filename":
                "{Data->Patient->lastname}_{Data->Patient->firstname}_{Data->Patient->mi}_{Data->Patient->chart}_{CurrentDateTime}",
            "paper_size": "Letter",
            "units": "mm",
            "orientation": "P"
        },
        "metadata": [
            "SetAuthor(\'Xtract Solutions\')",
            "SetTitle(\'Dialy Injection Report\')",
            "SetSubject(\'A report of {Data->Patient->firstname} {Data->Patient->lastname}\'s injections on {Data->report_date}\'"
        ],
        "header": [
            "Image(\'storage/app/reports/logo.png\',160,5,50)",
            "SetXY(15,{GetY})",
            "SetFont(\'Arial\',\'\',20)",
            "Write(10,\'Daily Injection Report\')",
            "Ln()",
            "FontAwesome(\'fas\',\'f007\',12)",
            "SetXY(15,{GetY})",
            "Template(\'patient_line\')",
            "SetFont(\'Arial\',\'\',10)",
            "Write(5,\'Visit: {Data->Report_date}\')",
            "SetDrawColor(0,100,250)",
            "SetLineWidth(.3)",
            "Line(25,{GetY+10},190,{GetY+10})",
            "SetXY({GetX},25)"
        ],
        "footer": [
            "SetXY(15,-15)",
            "Template(\'patient_line\')",
            "SetXY(15,-10)",
            "SetFont(\'Arial\',\'\',10)",
            "Write(5,\'Report generated: {Data->Generated_date}\')",
            "SetXY(-35,{GetY})",
            "Write(5,\'Page {PageNo} of {TotalPages}\')"
        ],
        "body": [
            "Template(\'providers\')",
            "Template(\'injections_given\')",
            "Template(\'reactions\')",
            "Template(\'questionnaire_answers\')"
        ],
        "providers": [
            "SetXY(15,{GetY+15})",
            "FontAwesome(\'fas\',\'f0f0\',12)",
            "SetXY(25,{GetY})",
            "SetFont(\'Arial\',\'\',10)",
            "Write(6,\'Injections administered by: {InjectingProviders}\')",
            "Ln()",
            "SetXY(25,{GetY})",
            "Write(6,\'Attending provider: {AttendingProviders}\')",
            "Ln()",
            "SetXY(25,{GetY})",
            "Write(6,\'Prescribing provider: {PrescribingProviders}\')",
            "Ln()",
            "SetDrawColor(0,0,0)",
            "SetLineWidth(.1)",
            "Line(25,{GetY+10},190,{GetY+10})"
        ],
        "reactions": [
            "SetXY(15,{GetY+15})",
            "FontAwesome(\'fal\',\'f071\',12)",
            "SetXY(25,{GetY})",
            "SetFont(\'Arial\',\'B\',12)",
            "Write(6,\'Reactions\')",
            "SetFont(\'Arial\',\'\',10)",
            "Ln()",
            "SetXY(30,{GetY+2})",
            "SetFont(\'Arial\',\'UB\',10)",
            "Write(5,\'Systemic\')",
            "Systemics(\'systemic\')",
            "Ln()",
            "SetXY(30,{GetY+2})",
            "SetFont(\'Arial\',\'UB\',10)",
            "Write(5,\'Local\')",
            "Locals(\'local\')",
            "Ln(2)",
            "SetDrawColor(0,0,0)",
            "SetLineWidth(.1)",
            "Line(25,{GetY+10},190,{GetY+10})"
        ],
        "systemic": [
            "SetXY(60,{GetY+1})",
            "SetFont(\'Arial\',\'\',10)",
            "MultiCell(50,4,\'{Data->compound->name}\')",
            "Cell(50,4,\'{Data->systemic_reaction}\')"
        ],
        "local": [
            "SetXY(60,{GetY+1})",
            "SetFont(\'Arial\',\'\',10)",
            "MultiCell(50,4,\'{Data->compound->name}\')",
            "Cell(50,4,\'{Data->local_reaction}\')"
        ],
        "questionnaire_answers": [
            "SetXY(15,{GetY+15})",
            "FontAwesome(\'fal\',\'f46d\',12)",
            "SetXY(25,{GetY})",
            "SetFont(\'Arial\',\'B\',12)",
            "Write(6, \'Questionnaire responses\')",
            "SetFont(\'Arial\',\'\',10)",
            "Ln()",
            "Template(\'answer\',{Data->Answers})",
            "SetDrawColor(0,0,0)",
            "SetLineWidth(.1)",
            "Line(25,{GetY+10},190,{GetY+10})"
        ],
        "answer": [
            "SetXY(20,{GetY+5})",
            "SetFont(\'Arial\',\'\',10)",
            "MultiCell(85,4,{Data->question})",
            "SetFont(\'Arial\',\'B\',10)",
            "MultiCell(85,4,{Data->response})",
            "Ln(2)"
        ],
        "injections_given": [
            "SetXY(15,{GetY+15})",
            "FontAwesome(\'fal\',\'f48e\',12)",
            "SetXY(25,{GetY})",
            "SetFont(\'Arial\',\'B\',12)",
            "Write(6,\'Injections given\')",
            "Template(\'injection\',{Data->Injections})",
            "SetDrawColor(0,0,0)",
            "SetLineWidth(.1)",
            "Line(25,{GetY+10},190,{GetY+10}"
        ],
        "injection": [
            "SetFont(\'Arial\',\'\',10)",
            "Ln()",
            "SetXY(20,{GetY+5})",
            "MultiCell(45,3,\'{Data->compound->prescription->prescription_number} - {Data->compound->name}\')",
            "Cell(18,3,\'{Data->dose} mL\')",
            "Cell(26,3,\'{Data->site}\')",
            "Cell(36,3,\'{Data->datetime_administered}\')",
            "MultiCell(50,3,\'{Data->notes_patient}\')",
            "Ln(2)"
        ],
        "patient_line": [
            "SetFont(\'Arial\',\'B\',10)",
            "Write(5, \'{Data->Patient->firstname} {Data->Patient->mi}. {Data->Patient->lastname}\')",
            "SetFont(\'Arial\',\'\',10)",
            "SetTextColor(0,100,250)",
            "Write(5,\' | \')",
            "SetTextColor(0,0,0)",
            "Write(5,\'DOB: {Data->Patient->dob}\')",
            "SetTextColor(0,100,250)",
            "Write(5,\' | \')",
            "SetTextColor(0,0,0)",
            "Write(5,\'MRN: {Data->Patient->chart}\')",
            "Ln()"
        ]
    }';

    public static $defaultMixReceipt =
    '{
        "file_info": {
            "filename":
                "{Data->Patient->lastname}_{Data->Patient->firstname}_{Data->Patient->mi}_{Data->Patient->chart}_{CurrentDateTime}",
            "paper_size": "Letter",
            "units": "mm",
            "orientation": "P"
        },
        "metadata": [
            "SetAuthor(\'Xtract Solutions\')",
            "SetTitle(\'Mix receipt\')",
            "SetSubject(\'A mix recepit for {Data->Patient->firstname} {Data->Patient->lastname}\'s prescription # {$Data->Prescription->prescription_num"\')
        ],
        "header": [
            "Image(\'storage/app/reports/logo.png\',160,5,50)",
            "SetXY(15,{GetY})",
            "SetFont(\'Arial\',\'\',20)",
            "Write(10,\'Allergy Prescription Mix Receipt\')",
            "Ln()",
            "FontAwesome(\'fas\',\'f007\',12)",
            "SetXY(15,{GetY})",
            "Template(\'patient_line\')",
            "SetFont(\'Arial\',\'\',10)",
            "Write(5,\'Visit: {Data->Report_date}\')",
            "SetDrawColor(0,100,250)",
            "SetLineWidth(.3)",
            "Line(25,{GetY+10},190,{GetY+10})",
            "SetXY({GetX},25)"
        ],
        "footer": [
            "SetXY(15,-15)",
            "Template(\'patient_line\')",
            "SetXY(15,-10)",
            "SetFont(\'Arial\',\'\',10)",
            "Write(5,\'Report generated: {Data->Generated_date}\')",
            "SetXY(-35,{GetY})",
            "Write(5,\'Page {PageNo} of {TotalPages}\')"
        ],
        "body": [
            "SetXY(15,{GetY+15})",
            "SetFont(\'Arial\',\'\',16)",
            "Write(8,\'Prescription Overview\')",
            "Template(\'provider_details\')",
            "Template(\'mix_details\')",
            "Template(\'prescription_details\')"
        ],
        "provider_details": [
            "SetXY(15,{GetY+10})",
            "FontAwesome(\'fal\',\'f0f1\',12)",
            "SetXY(25,{GetY})",
            "SetFont(\'Arial\',\'\',10)",
            "Write(6, \'Prescribing provider: {Data->TreatmentSet->prescription->provider->first} {Data->TreatmentSet->prescription->provider->mi} {Data->TreatmentSet->prescription->provider->last}, {Data->TreatmentSet->prescription->provider->suffix}\')",
            "SetXY(25,{GetY})",
            "SetDrawColor(0,0,0)",
            "SetLineWidth(.1)",
            "Line(25,{GetY+10},190,{GetY+10})"
        ],
        "mix_details": [
            "SetXY(15,{GetY+15})",
            "FontAwesome(\'fal\',\'f5a7\',12)",
            "SetXY(25,{GetY})",
            "SetFont(\'Arial\',\'\',10)",
            "Write(6,\'Mixed by: {MixedBy}\')",
            "Ln()",
            "SetXY(25,{GetY})",
            "Write(6,\'Mixed on: {MixedOn}\')",
            "Ln()",
            "SetXY(25,{GetY})",
            "Write(6,\'Expiration: {LabelOutdate}\')",
            "SetXY(25,{GetY})",
            "SetDrawColor(0,0,0)",
            "SetLineWidth(.1)",
            "Line(25,{GetY+10},190,{GetY+10})"
        ],
        "prescription_details": [
            "SetXY(15,{GetY+15})",
            "FontAwesome(\'fal\',\'f486\',12)",
            "SetXY(25,{GetY})",
            "SetFont(\'Arial\',\'\',10)",
            "Write(6,\'Prescription #: {Data->TreatmentSet->prescription->prescription_number}\')",
            "Ln()",
            "SetXY(25,{GetY})",
            "Write(6,\'Bottle name: {SetName}\')",
            "Ln()",
            "SetXY(25,{GetY})",
            "Write(6,\'Treatment plan: {Data->TreatmentSet->prescription->treatment_plan->name}\')",
            "Ln()",
            "SetXY(25,{GetY})",
            "Write(6,\'Vial size: {SetSize}\')",
            "Template(\'dilutions_circles\', {Data->TreatmentSet->vials})",
            "Template(\'extracts\')",
            "Template(\'diluents\')",
            "Template(\'mix_totals\')"
        ],
        "dilutions_circles": [
            "SetFont(\'Arial\',\'\,7)",
            "SetFillColor({Data->color})",
            "Text({GetX+45},{GetY-12},\'1:{Data->dilution}\')",
            "Circle({GetX+50},{GetY-6},5,\'FD\')",
            "SetFillColor(0,0,0)",
            "SetX({GetX+12})"
        ],
        "extracts": [
            "Ln()",
            "Ln()",
            "SetX({GetX+5})",
            "SetFont(\'Arial\',\'B\',10)",
            "DashedRect({GetX},{GetY},{GetX+185},{GetY+6},.25,100)",
            "SetX({GetX+2})",
            "MultiCell(55,6,\'Extract\')",
            "MultiCell(20,6,\'Dose\')",
            "MultiCell(30,6,\'Dilution\')",
            "MultiCell(20,6,\'Units\')",
            "MultiCell(30,6,\'Lot #\')",
            "MultiCell(30,6,\'Outdate\')",
            "Ln()",
            "Template(\'extract\',{Data->TreatmentSet->vials[0]->vials})"
        ],
        "extract": [
            "SetX(15)",
            "SetFont(\'Arial\',\'\',10)",
            "MultiCell(55,4,\'{Data->inventory->extract->name}\', 1)",
            "MultiCell(20,4,\'{Data->dosing->dose}\', 1)",
            "MultiCell(30,4,\'{Data->inventory->extract->dilution}\', 1)",
            "MultiCell(20,4,\'{Data->inventory->extract->unit_type->name}\', 1)",
            "MultiCell(30,4,\'{Data->inventory->lotnumber}\', 1)",
            "MultiCell(30,4,\'{Data->out_date}\', 1)",
            "SetFont(\'Arial\',\'\',10)",
            "Ln()"
        ],
        "diluents": [],
        "mix_totals": [],
        "patient_line": [
            "SetFont(\'Arial\',\'B\',10)",
            "Write(5, \'{Data->Patient->firstname} {Data->Patient->mi}. {Data->Patient->lastname}\')",
            "SetFont(\'Arial\',\'\',10)",
            "SetTextColor(0,100,250)",
            "Write(5,\' | \')",
            "SetTextColor(0,0,0)",
            "Write(5,\'DOB: {Data->Patient->dob}\')",
            "SetTextColor(0,100,250)",
            "Write(5,\' | \')",
            "SetTextColor(0,0,0)",
            "Write(5,\'MRN: {Data->Patient->chart}\')",
            "Ln()"
        ]
    }';

    public static $defaultOrderMixReceipt = '
    {
        "file_info": {
            "filename":
                "{Data->Patient->lastname}_{Data->Patient->firstname}_{Data->Patient->mi}_{Data->Patient->chart}_{CurrentDateTime}",
            "paper_size": "Letter",
            "units": "mm",
            "orientation": "P"
        },
        "metadata": [
            "SetAuthor(\'Xtract Solutions\')",
            "SetTitle(\'Order mix receipt\')",
            "SetSubject(\'An order mix receipt for purchase_order # {$Data->Order->order_id}\'"
        ],
        "header": [
            "Image(\'storage/app/reports/logo.png\',160,5,50)",
            "SetXY(15,{GetY})",
            "SetFont(\'Arial\',\'\',20)",
            "Write(10,\'Purchase Order Mix Receipt\')",
            "Ln()",
            "FontAwesome(\'fas\',\'f007\',12)",
            "SetXY(15,{GetY})",
            "Template(\'patient_line\')",
            "SetFont(\'Arial\',\'\',10)",
            "Write(5,\'Visit: {Data->Report_date}\')",
            "SetDrawColor(0,100,250)",
            "SetLineWidth(.3)",
            "Line(25,{GetY+10},190,{GetY+10})",
            "SetXY({GetX},25)"
        ],
        "footer": [
            "SetXY(15,-15)",
            "Template(\'patient_line\')",
            "SetXY(15,-10)",
            "SetFont(\'Arial\',\'\',10)",
            "Write(5,\'Report generated: {Data->Generated_date}\')",
            "SetXY(-35,{GetY})",
            "Write(5,\'Page {PageNo} of {TotalPages}\')"
        ],
        "body": ["Template(\'prescription\',{Data->Order->treatment_sets})"],
        "prescription": [
            "SetXY(15,{GetY+15})",
            "SetFont(\'Arial\',\'\',16)",
            "Write(8,\'Prescription Overview\')",
            "Template(\'provider_details\',{Data->prescription->provider})",
            "Template(\'mix_details\',{Data->vials[0]->vials[0]})",
            "Template(\'prescription_details\',{Data})"
        ],
        "provider_details": [
            "SetXY(15,{GetY+10})",
            "FontAwesome(\'fal\',\'f0f1\',12)",
            "SetXY(25,{GetY})",
            "SetFont(\'Arial\',\'\',10)",
            "Write(6, \'Prescribing provider: {Data->first} {Data->mi} {Data->last}, {Data->suffix}\')",
            "SetXY(25,{GetY})",
            "SetDrawColor(0,0,0)",
            "SetLineWidth(.1)",
            "Line(25,{GetY+10},190,{GetY+10})"
        ],
        "mix_details": [
            "SetXY(15,{GetY+15})",
            "FontAwesome(\'fal\',\'f5a7\',12)",
            "SetXY(25,{GetY})",
            "SetFont(\'Arial\',\'\',10)",
            "Write(6,\'Mixed by: {Data->user->displayname}\')",
            "Ln()",
            "SetXY(25,{GetY})",
            "Write(6,\'Mixed on: {Data->mix_date}\')",
            "Ln()",
            "SetXY(25,{GetY})",
            "Write(6,\'Expiration: {Data->label_out_date}\')",
            "SetXY(25,{GetY})",
            "SetDrawColor(0,0,0)",
            "SetLineWidth(.1)",
            "Line(25,{GetY+10},190,{GetY+10})"
        ],
        "prescription_details": [
            "SetXY(15,{GetY+15})",
            "FontAwesome(\'fal\',\'f486\',12)",
            "SetXY(25,{GetY})",
            "SetFont(\'Arial\',\'\',10)",
            "Write(6,\'Prescription #: {Data->prescription->prescription_number}\')",
            "Ln()",
            "SetXY(25,{GetY})",
            "Write(6,\'Bottle name: {Data->vials[0]->name}\')",
            "Ln()",
            "SetXY(25,{GetY})",
            "Write(6\Treatment plan: {Data->prescription->treatment_plan->name}\')",
            "Ln()",
            "SetXY(25,{GetY})",
            "Write(6,\'Vial size: {Data->vials[0]->size}\')",
            "Template(\'dilutions_circles\', {Data->vials})",
            "Template(\'extracts\', {Data->vials[0]})"
        ],
        "dilutions_circles": [
            "SetFont(\'Arial\',\'\',7)",
            "SetFillColor({Data->color})",
            "Text({GetX+45},{GetY-12},\'1:{Data->dilution}\')",
            "Circle({GetX+50},{GetY-6},5,\'FD\')",
            "SetFillColor(0,0,0)",
            "SetX({GetX+12})"
        ],
        "extracts": [
            "Ln()",
            "Ln()",
            "SetX({GetX+5})",
            "SetFont(\'Arial\',\'B\',10)",
            "DashedRect({GetX},{GetY},{GetX+185},{GetY+6},.25,100)",
            "SetX({GetX+2})",
            "MultiCell(55,6,\'Extract\')",
            "MultiCell(20,6,\'Dose\')",
            "MultiCell(30,6,\'Dilution\')",
            "MultiCell(20,6,\'Units\')",
            "MultiCell(30,6,\'Lot #\')",
            "MultiCell(30,6,\'Outdate\')",
            "Ln()",
            "Template(\'extract\',{Data->vials})"
        ],
        "extract": [
            "SetX(15)",
            "SetFont(\'Arial\',\'\',10)",
            "MultiCell(55,4,\'{Data->inventory->extract->name}\', 1)",
            "MultiCell(20,4,\'{Data->dosing->dose}\', 1)",
            "MultiCell(30,4,\'{Data->inventory->extract->dilution}\', 1)",
            "MultiCell(20,4,\'{Data->inventory->extract->unit_type->name}\', 1)",
            "MultiCell(30,4,\'{Data->inventory->lotnumber}\', 1)",
            "MultiCell(30,4,\'{Data->out_date}\', 1)",
            "SetFont(\'Arial\',\'\',10)",
            "Ln()"
        ],
        "patient_line": [
            "SetFont(\'Arial\',\'B\',10)",
            "Write(5, \'{Data->Patient->firstname} {Data->Patient->mi}. {Data->Patient->lastname}\')",
            "SetFont(\'Arial\',\'\',10)",
            "SetTextColor(0,100,250)",
            "Write(5,\' | \')",
            "SetTextColor(0,0,0)",
            "Write(5,\'DOB: {Data->Patient->dob}\')",
            "SetTextColor(0,100,250)",
            "Write(5,\' | \')",
            "SetTextColor(0,0,0)",
            "Write(5,\'MRN: {Data->Patient->chart}\')",
            "Ln()"
        ]
    }';

    public static $defaultOrderReceipt =
    '{
        "file_info": {
            "filename":
                "{Data->Patient->lastname}_{Data->Patient->firstname}_{Data->Patient->mi}_{Data->Patient->chart}_{CurrentDateTime}",
            "paper_size": "Letter",
            "units": "mm",
            "orientation": "P"
        },
        "metadata": [
            "SetAuthor(\'Xtract Solutions\')",
            "SetTitle(\'Order receipt\')",
            "SetSubject(\'An order receipt for purchase_order # {$Data->Order->order_id}"\')
        ],
        "header": [
            "Image(\'storage/app/reports/logo.png\',160,5,50)",
            "SetXY(15,{GetY})",
            "SetFont(\'Arial\',\'\',20)",
            "Write(10,\'Purchase Order Receipt\')",
            "Ln()",
            "FontAwesome(\'fas\',\'f007\',12)",
            "SetXY(15,{GetY})",
            "Template(\'patient_line\')",
            "SetFont(\'Arial\',\'\',10)",
            "Write(5,\'Visit: {Data->Report_date}\')",
            "SetDrawColor(0,100,250)",
            "SetLineWidth(.3)",
            "Line(25,{GetY+10},190,{GetY+10})",
            "SetXY({GetX},25)"
        ],
        "footer": [
            "SetXY(15,-15)",
            "Template(\'patient_line\')",
            "SetXY(15,-10)",
            "SetFont(\'Arial\',\'\',10)",
            "Write(5,\'Report generated: {Data->Generated_date}\')",
            "SetXY(-35,{GetY})",
            "Write(5,\'Page {PageNo} of {TotalPages}\')"
        ],
        "body": ["Template(\'prescription\',{Data->Order->treatment_sets})"],
        "prescription": [
            "SetXY(15,{GetY+15})",
            "SetFont(\'Arial\',\'\',16)",
            "Write(8,\'Prescription Overview\')",
            "Template(\'provider_details\',{Data->prescription->provider})",
            "Template(\'prescription_details\',{Data})"
        ],
        "provider_details": [
            "SetXY(15,{GetY+10})",
            "FontAwesome(\'fal\',\'f0f1\',12)",
            "SetXY(25,{GetY})",
            "SetFont(\'Arial\',\'\',10)",
            "Write(6, \'Prescribing provider: {Data->first} {Data->mi} {Data->last}, {Data->suffix}\')",
            "SetXY(25,{GetY})",
            "SetDrawColor(0,0,0)",
            "SetLineWidth(.1)",
            "Line(25,{GetY+10},190,{GetY+10})"
        ],
        "prescription_details": [
            "SetXY(15,{GetY+15})",
            "FontAwesome(\'fal\',\'f486\',12)",
            "SetXY(25,{GetY})",
            "SetFont(\'Arial\',\'\',10)",
            "Write(6,\'Prescription #: {Data->prescription->prescription_number}\')",
            "Ln()",
            "SetXY(25,{GetY})",
            "Write(6,\'Bottle name: {Data->vials[0]->name}\')",
            "Ln()",
            "SetXY(25,{GetY})",
            "Write(6,\'Treatment plan: {Data->prescription->treatment_plan->name}\')",
            "Ln()",
            "SetXY(25,{GetY})",
            "Write(6,\'Vial size: {Data->vials[0]->size}\')",
            "Template(\'dilutions_circles\', {Data->vials})",
            "Template(\'extracts\', {Data->vials[0]})"
        ],
        "dilutions_circles": [
            "SetFont(\'Arial\',\'\',7)",
            "SetFillColor({Data->color})",
            "Text({GetX+45},{GetY-12},\'1:{Data->dilution}\')",
            "Circle({GetX+50},{GetY-6},5,\'FD\')",
            "SetFillColor(0,0,0)",
            "SetX({GetX+12})"
        ],
        "extracts": [
            "Ln()",
            "Ln()",
            "SetX({GetX+5})",
            "SetFont(\'Arial\',\'B\',10)",
            "DashedRect({GetX+25},{GetY},{GetX+150},{GetY+6},.25,70)",
            "SetX({GetX+27})",
            "MultiCell(55,6,\'Extract\')",
            "MultiCell(20,6,\'Dose\')",
            "MultiCell(30,6,\'Dilution\')",
            "MultiCell(20,6,\'Units\')",
            "Ln()",
            "Template(\'extract\',{Data->vials})"
        ],
        "extract": [
            "SetX(40)",
            "SetFont(\'Arial\',\'\',10)",
            "MultiCell(55,4,\'{Data->inventory->extract->name}\', 1)",
            "MultiCell(20,4,\'{Data->dosing->dose}\', 1)",
            "MultiCell(30,4,\'{Data->inventory->extract->dilution}\', 1)",
            "MultiCell(20,4,\'{Data->inventory->extract->unit_type->name}\', 1)",
            "SetFont(\'Arial\',\'\',10)",
            "Ln()"
        ],
        "patient_line": [
            "SetFont(\'Arial\',\'B\',10)",
            "Write(5, \'{Data->Patient->firstname} {Data->Patient->mi}. {Data->Patient->lastname}\')",
            "SetFont(\'Arial\',\'\',10)",
            "SetTextColor(0,100,250)",
            "Write(5,\' | \')",
            "SetTextColor(0,0,0)",
            "Write(5,\'DOB: {Data->Patient->dob}\')",
            "SetTextColor(0,100,250)",
            "Write(5,\' | \')",
            "SetTextColor(0,0,0)",
            "Write(5,\'MRN: {Data->Patient->chart}\')",
            "Ln()"
        ]
    }';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('template');
        Schema::create('template', function (Blueprint $table) {
            $table->integer('template_id')->autoIncrement();
            $table->text('template');
            $table->string('extension', 3);
            $table->string('name', 64)->unique();
            $table->string('queriesCSV', 128);
        });
        $DailyInjTemplate = new Template();
        $DailyInjTemplate->template = $this::$defaultInjectionTemplate;
        $DailyInjTemplate->extension = 'pdf';
        $DailyInjTemplate->name = 'Daily injection report';
        $DailyInjTemplate->queriesCSV = 'patient,dailyInjection,tracking,questionnaire';
        $DailyInjTemplate->save();

        $MixReceipt = new Template();
        $MixReceipt->template = $this::$defaultMixReceipt;
        $MixReceipt->extension = 'pdf';
        $MixReceipt->queriesCSV = 'patient,treatmentset';
        $MixReceipt->name = 'Mix receipt';
        $MixReceipt->save();

        $orderMixReceipt = new Template();
        $orderMixReceipt->template = $this::$defaultOrderMixReceipt;
        $orderMixReceipt->extension = 'pdf';
        $orderMixReceipt->name = 'Order mix receipt';
        $orderMixReceipt->queriesCSV = 'patient,purchaseorder';
        $orderMixReceipt->save();

        $orderReceipt = new Template();
        $orderReceipt->template = $this::$defaultOrderReceipt;
        $orderReceipt->extension = 'pdf';
        $orderReceipt->name = 'Order receipt';
        $orderReceipt->queriesCSV = 'patient,purchaseorder';
        $orderReceipt->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('template');
        Schema::enableForeignKeyConstraints();
    }
}
