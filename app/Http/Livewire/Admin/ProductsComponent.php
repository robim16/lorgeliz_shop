<?php

namespace App\Http\Livewire\Admin;

use App\Producto;
use Illuminate\Http\Request;
use Livewire\Component;

class ProductsComponent extends Component
{

    public $busqueda;

    public function render()
    {
        return view('livewire.admin.products-component');
    }


    public function getProductosProperty()
    {

        $productos = Producto::when($this->busqueda, function ($query) {
            $query->where('productos.nombre', 'like', "%{$this->busqueda}%")
                ->orWhere('productos.id', 'like', "%{$this->busqueda}%")
                ->orWhere('productos.descripcion_corta', 'like', "%{$this->busqueda}%");
        })
            ->with('colors:id')
            ->withCount('colors')
            ->paginate(10);



        foreach ($productos as $producto) {
            $producto->colors[0]->pivot->load(['imagenes' => function ($query) {
                $query->select('id', 'url', 'imageable_id')
                    ->limit(1);
            }]);
        }


        return $productos;
    }
}
