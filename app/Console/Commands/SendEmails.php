<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use App\Resource;
use Carbon\Carbon;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sendemails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send auto e-mails to resources';

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
     * @param  \App\DripEmailer  $drip
     * @return mixed
     */
    public function handle()
    {
        // $a = Resource::whereDate('last_login','<',Carbon::now()->subSeconds(10))->count();
        $a = Resource::all()->count();

        echo($a);
    }
}