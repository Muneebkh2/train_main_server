<?php

namespace App\Jobs;
use App\Message;

class SendEmail extends Job
{
    protected $message;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Mesaage $message)
    {
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        echo "Test" . $this->message->body;
    }
}
