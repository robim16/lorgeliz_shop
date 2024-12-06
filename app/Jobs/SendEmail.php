<?php

namespace App\Jobs;

use App\Mail\AdminDevolucionMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $details;
    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details, $user)
    {
        $this->details = $details;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->user->email)
        ->send(new AdminDevolucionMail($this->details));
    }
}
