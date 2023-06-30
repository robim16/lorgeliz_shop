<?php

namespace App\Http\Livewire\Admin;

use App\Venta;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class VentasComponent extends Component
{

    public $busqueda;

    public $estado;

    public function render()
    {
        return view('livewire.admin.ventas-component');
    }


    public function getVentasProperty()
    {

        return Venta::when($this->estado, function ($query) {
            return $query->where('estado', $this->estado);
            // ->with('cliente');
        })
            ->when($this->busqueda,  function ($query) {
                return $query->orWhere('valor', 'like', "%{$this->busqueda}%")
                    ->orWhereHas('cliente.user', function (Builder $query) {
                        $query->where('nombres', 'like', "%{$this->busqueda}%")
                            ->where('apellidos', 'like', "%{$this->busqueda}%");
                    });
            })
            ->with(['pedido:id,venta_id', 'cliente:id,user_id', 'cliente.user:id,nombres,apellidos'])
            ->withCount('devoluciones')
            ->orderBy('id', 'DESC')
            ->paginate(5);
    }
}
