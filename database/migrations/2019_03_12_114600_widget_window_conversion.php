<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Config;

class WidgetWindowConversion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // find all effected configs
        $Configs = Config::where('section', 'dashboard')->orWhere('section','patientDisplay')->orWhere('section', 'adminDisplay');
        foreach($Configs as $Config){
            $Config->name = strtoupper($Config->name) === "VIEWS" ? "screens" : $Config->name;
            $Config->name = strtoupper($Config->name) === "WIDGETS" ? "windows" : $Config->name;
            $Config->value = str_replace('view','screen',$Config->value);
            $Config->value = str_replace('View', 'screen', $Config->value);
            $Config->value = str_replace('widget','window',$Config->value);
            $Config->value = str_replace('Widget', 'window', $Config->value);
            $Config->value = str_replace('overscreen','overview',$Config->value);
            $Config->value = str_replace('Overscreen', 'Overview', $Config->value);
            $Config->value = str_replace('rescreen','review',$Config->value);
            $Config->value = str_replace('Rescreen', 'Review', $Config->value);
            $Config->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // find all effected configs
        $Configs = Config::where('section', 'dashboard')->orWhere('section','patientDisplay')->orWhere('section', 'adminDisplay');
        foreach($Configs as $Config){
            $Config->name = strtoupper($Config->name) === "SCREENS" ? "views" : $Config->name;
            $Config->name = strtoupper($Config->name) === "WINDOWS" ? "widgets" : $Config->name;
            $Config->value = str_replace('screen','view',$Config->value);
            $Config->value = str_replace('window','widget',$Config->value);
            $Config->save();
        }
    }
}
