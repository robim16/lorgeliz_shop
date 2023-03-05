<?php

namespace App\Jobs;

use App\Mail\ClientePrivateMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendClientePrivateMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $mensaje;
    protected $user;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($mensaje, $user)
    {
        $this->mensaje = $mensaje;
        $this->user = $user;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
        
            Mail::to($this->user->email)->send(new ClientePrivateMail($this->user->nombres, 
                $this->mensaje));

                Log::info('mensaje para el cliente: '.$this->mensaje);

        } catch (\Exception $e) {
            Log::debug('Error enviando email del admin al cliente.Error: '.$e);
        }
    }
}
