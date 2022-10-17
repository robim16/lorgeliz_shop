<?php

namespace App\Http\Controllers\Admin\Api;

use App\Imagene;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImagenController extends Controller
{
    public function destroy(Request $request,$id)
    {
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

        try {

            $image = Imagene::find($id);
    
            $eliminar = Storage::disk('public')->delete($image->url); // se elimina del directorio
    
            $image->delete(); // se elimina de la bd
    
            return "eliminado id:".$id.' '.$eliminar;
            
            
        } catch (\Exception $e) {
            //throw $th;
        }

    }
}
