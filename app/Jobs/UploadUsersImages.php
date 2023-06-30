<?php

namespace App\Jobs;

use App\Imagene;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class UploadUsersImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $request;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $request)
    {
        $this->user = $user;
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        $imagen = $this->request;
        $nombre = time().'_'.$imagen->getClientOriginalName();
        
        $path = Storage::disk('public')->putFileAs("imagenes/users/" . $this->user->id, $imagen, $nombre);

        $img = new Imagene();
        
        $img->url = $path;
        $img->imageable_type = 'App\User';
        $img->imageable_id = $this->user->id;

        $img->save();
       
    }
}
