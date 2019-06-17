<?php

namespace App\Console\Commands;

use App\Models\Config;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class exportUI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:exportUI {section : dashboard, patientDisplay, adminDisplay, or all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export the ui config to storage/app/export.';

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
        $config = app()->make('config');
        $database = $config->get('database.connections.mysql.database');
        $host = $config->get('database.connections.mysql.host');
        $section= $this->argument('section');

        $Confirmed = $this->ask("This will export ".$section." config(s) from database: ".$database." on host: ".$host.". Would you like to continue? (Yes/No)");
        if (strtolower($Confirmed) !== 'yes') {
            $this->comment('No data has been imported.');
            return;
        }

        switch (strtolower($section)) {
            case 'dashboard':
            case 'adminDisplay':
            case 'patientDisplay':
                $data = Config::where('section', $section)
                    ->select(['name','section','value','compname','app'])
                    ->get();
                break;
            case 'all':
                $data = Config::where('section', 'dashboard')
                    ->orWhere('section', 'adminDisplay')
                    ->orWhere('section', 'patientDisplay')
                    ->select(['name','section','value','compname','app'])
                    ->get();
                break;
            default:
                $this->error("Invalid export section");
                return;
        }
        try {
            Storage::put('export/'.$section.'_ui_config_export.json', json_encode($data));
            $this->comment('Succesfully exported to storage\app\export\\'.$section.'_ui_config_export.json');
        } catch (Exception $e) {
            $this->error("Error writing output file. Maybe bad permissions on storage\app\export directory?");
        }
    }
}
