<?php

namespace App\Console\Commands;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;
use Illuminate\Console\Command;
use App\Models\PatientTemp;
use App\Models\Patient;
use App\Models\Login;
use App\Models\Image;

class AnonomizeDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:anonomize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the patient table with random patient information.';

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

        $Confirmed = $this->ask("This will anonomize database: ".$database." on host: ".$host.". Are you sure? (Yes/No)");

        if (strtolower($Confirmed) !== 'yes') {
            $this->comment("Anonomization has been cancelled. No changes made.");
            return;
        }
        // prepare for multiple outputs at once and setup progress bar style.
        $output = new ConsoleOutput;
        ProgressBar::setFormatDefinition('custom', ' %percent:3s%% [%bar%] %current%/%max% %message%');

        $Patients = Patient::get();
        $PatientsBar = $this->initProgressBar($output, $Patients);

        $Logins = Login::with('patient')->get();
        $LoginsBar = $this->initProgressBar($output, $Logins);

        $PatientTemps = PatientTemp::get();
        $PatientTempsBar = $this->initProgressBar($output, $PatientTemps);

        $this->anonomizePatients($Patients, $PatientsBar);
        $this->anonomizeLogins($Logins, $LoginsBar);
        $this->anonomizePatientTemps($PatientTemps, $PatientTempsBar);

        $this->line("\n");
        $this->comment('Anonomization complete!');
    }

    private function anonomizeLogins($Logins, $ProgressBar)
    {
        foreach ($Logins as $idx => $Login) {
            $Login->name = $Login->patient->displayname;
            $Login->save();
            $ProgressBar->advance();
        }
        $ProgressBar->finish();
    }

    private function anonomizePatients($Patients, $ProgressBar)
    {
        foreach ($Patients as $idx => $Patient) {
            if ($Patient->displayname === 'manualIn') {
                //don't update the manualIn patient
                $ProgressBar->advance();
                continue;
            }

            $Patient = $this->validObjectMaker(Patient::class, $Patient);

            if (!is_null($Patient->face_image_id)) {
                $Image = Image::find($Patient->face_image_id);
                $Patient->face_image_id = null;
                $Patient->save();
                $Image->save();
            } else {
                $Patient->save();
            }
            $ProgressBar->advance();
        }
        $ProgressBar->finish();
    }

    private function anonomizePatientTemps($PatientTemps, $ProgressBar)
    {
        foreach ($PatientTemps as $idx => $PatientTemp) {
            $ProgressBar->advance();
            $PatientTemp = $this->validObjectMaker(PatientTemp::class, $PatientTemp);
            $PatientTemp->save();
        }
        $ProgressBar->finish();
    }

    private function validObjectMaker($class, $Instance, $replacements = [])
    {
        // this is the 10x retry to create a valid object
        for ($i=0; $i < 10; $i++) {
            $updates = factory($class)->make($replacements);
            if ($Instance->validate($updates->toArray(), $Instance[$Instance->getKeyName()])) {
                break;
            } elseif ($i === 9) {
                // we tried 9 times and could create a unique patient, give up.
                $this->error('Abort! Unable to create more unique '.$Instance->getTable().'s. Most likely your database is to large.');
                return;
            }
        }
        $Instance->update($updates->toArray());
        return $Instance;
    }

    private function initProgressBar($output, $Objects)
    {
        $Section = $output->section();
        $Total = $Objects->count();
        $Bar = new ProgressBar($Section, $Total);
        $Bar->setFormat('custom');
        $Bar->setMessage($Objects[0]->getTable().' rows updated.');
        $Bar->setBarCharacter('▓');
        $Bar->setEmptyBarCharacter('░');
        $Bar->setProgressCharacter('');
        $Bar->start();
        return $Bar;
    }
}
