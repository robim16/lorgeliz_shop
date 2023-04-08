<?php

namespace App\Http\Livewire\Admin;

use App\Venta;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class IndexInformeClientes extends Component
{

    public $busqueda;


    public function render()
    {
        return view('livewire.admin.index-informe-clientes');
    }


    public function getVentasProperty()
    {

        return Venta::when($this->busqueda, function ($query)  {
            return $query->whereHas('cliente.user',  function (Builder $query) {
                $query->where('nombres','like',"%{$this->busqueda}%")
                    ->orWhere('apellidos','like',"%{$this->busqueda}%")
                    ->orWhere('telefono','like',"%{$this->busqueda}%")
                    ->orWhere('email','like',"%{$this->busqueda}%");
            });
        })
        // ->when($this->busqueda, function ($query)  {
        //     return $query->whereHas('cliente.user',  function (Builder $query) {
        //         $query->where('apellidos','like',"%{$this->busqueda}%");
        //     });
        // })
        // ->when($this->busqueda, function ($query)  {
        //     return $query->whereHas('cliente.user',  function (Builder $query) {
        //         $query->where('telefono','like',"%{$this->busqueda}%");
        //     });
        // })
        // ->when($this->busqueda, function ($query)  {
        //     return $query->whereHas('cliente.user',  function (Builder $query) {
        //         $query->where('email','like',"%{$this->busqueda}%");
        //     });
        // })
        ->with('cliente.user')
        ->select('cliente_id', DB::raw('COUNT(id) as cantidad'))
        ->groupBy('cliente_id')
        ->orderBy('cantidad', 'DESC')
        ->paginate(5);

       
    }
}
