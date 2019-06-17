<?php

namespace App\Console\Commands;

use App\Models\Config;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class importUI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:importUI {section : dashboard, patientDisplay, adminDisplay, or all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import the sections ui config file from storage/app/export.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $section= $this->argument('section');
        switch (strtolower($section)) {
            case 'dashboard':
            case 'adminDisplay':
            case 'patientDisplay':
            case 'all':
                if (!Storage::disk('local')->exists('export/'.$section.'_ui_config_export.json')) {
                    $this->error('No file with the name '.$section.'_ui_config_export.json was found in the storage\app\export directory.');
                    return;
                } else {
                    $this->import(strtolower($section));
                }

                break;
            default:
                $this->error("Invalid import section");
            
        }
    }

    private function import($section)
    {
        $config = app()->make('config');
        $database = $config->get('database.connections.mysql.database');
        $host = $config->get('database.connections.mysql.host');

        $Confirmed = $this->ask("This will import to database: ".$database." on host: ".$host.". Are you sure? (Yes/No)");
        if (strtolower($Confirmed) !== 'yes') {
            $this->comment('No data has been imported.');
            return;
        }

        if (
            ($section !== 'all' && Config::where('section', $section)->count() > 0) || ($section === 'all' && Config::where('section', 'dashboard')->orWhere('section', 'patientDisplay')->orWhere('section', 'adminDisplay')->count() > 0)) {
            $Confirmed = $this->ask("This database already contains configuration data for section: ".$section.". Continuing will overwrite the existing data. Continue? (Yes/No)");
            if (strtolower($Confirmed) !== 'yes') {
                $this->comment('No data has been imported.');
                return;
            } elseif ($section === 'all') {
                Config::where('section', 'dashboard')->orWhere('section', 'patientDisplay')->orWhere('section', 'adminDisplay')->delete();
            } else {
                Config::where('section', $section)->delete();
            }
        }
        try {
            $contents = Storage::get('export/'.$section.'_ui_config_export.json');
            foreach (json_decode($contents) as $configRow) {
                $Config = new Config;
                $Config->name = $configRow->name;
                $Config->section = $configRow->section;
                $Config->value = $configRow->value;
                $Config->compname = isset($configRow->compname) ? $configRow->compname : null;
                $Config->app = $configRow->app;
                $Config->save();
            }
            $this->comment('Data import complete.');
        } catch (Exception $e) {
            $this->error('An unknown error occurred during import.');
            $this->comment($e);
        }
    }
}
