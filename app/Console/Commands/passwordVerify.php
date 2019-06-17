<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class passwordVerify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'passwordVerify {password} {hash}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify a password against a hash. Enclose hash in tick marks.';

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
        $Password = $this->argument('password');
        $Hash = $this->argument('hash');
        if (password_verify($Password, $Hash)) {
            $this->comment("Password verfied!");
        } else {
            $this->error("Password is not a match to the provided hash.");
        }
    }
}
