<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class passwordHash extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:hashPasswords';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hash all user passwords in the database';

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
        $Confirmed = $this->ask("This will hash all user passwords in database: ".$database." on host: ".$host.". Are you sure? (Yes/No)");
        if (strtolower($Confirmed) !== 'yes') {
            $this->comment('No changes made.');
            return;
        }

        $Users = User::get();
        // check first 3 records for length
        $check = true;
        for ($i=0; $i < 3; $i++) {
            $check = $check && (strlen($Users[$i]->password) === 60 || is_null($Users[$i]->password));
        }

        if ($check) {
            $Confirmed = $this->ask("The first 3 passwords in the user table are null or 60 chars in length. This likely means the passwords are already hashed. Hashing a second time would make them unrecoverable. Do you want to continue? (Yes/No)");
            if (strtolower($Confirmed) !== 'yes') {
                $this->comment('No changes made.');
                return;
            }
        }

        $TotalUsers = $Users->count();
        $ProgressBar = $this->output->createProgressBar($TotalUsers);
        $ProgressBar->start();
        foreach ($Users as $idx => $User) {
            if (!is_null($User->password)) {
                $User->password = crypt($User->password, '$2a$10$Okp.dWAMf9fWjTGlW77MxOYDbbK81wA8YPSHjTTiohAFSiCAiJVF2');
                $User->save();
            }
            $ProgressBar->advance();
        }
        $ProgressBar->finish();
        $this->line("\n");
        $this->comment('Password hashing complete!');
    }
}
