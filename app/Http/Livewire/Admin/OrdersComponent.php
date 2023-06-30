<?php

namespace App\Http\Livewire\Admin;

use App\Pedido;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class OrdersComponent extends Component
{

    public $keyword;

    public $tipo;


    public function render()
    {
        return view('livewire.admin.orders-component');
    }


    public function getOrdersProperty()
    {
        return Pedido::whereHas('venta', function (Builder $query) {
            $query->where('estado', '!=', '3');
            
        })
            ->buscar($this->tipo, $this->keyword)
            ->with('venta.cliente.user')
            ->orderBy('created_at', 'DESC')
            ->paginate(5);

    }

}
