<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UploadProductsImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $producto;
    protected $request;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($producto, $request)
    {
       $this->producto = $producto;
       $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        $url_imagenes = [];

        if ($this->request->hasFile('imagenes')) {

            $imagenes = $this->request->file('imagenes');

            foreach ($imagenes as $imagen) {

                $nombre = time() . '_' . $imagen->getClientOriginalName();


                $image = Image::make($imagen)->encode('jpg', 75);
                $image->resize(530, 591, function ($constraint) {
                    $constraint->upsize();
                });

                

                $path = "imagenes/productos/producto_" . $this->producto->id . "/" . $nombre;

                Storage::disk('public')->put($path, $image->stream());

                $url_imagenes[]['url'] = $path;
            }

            return $url_imagenes;
        }
    }
}
