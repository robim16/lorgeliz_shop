<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ChatList extends Component
{
    public $usuario;
    public $mensajes;
    protected $ultimoId;
        
    protected $listeners = ['mensajeRecibido', 'cambioUsuario']; //escuchan los eventos
    
    public function mount()
    {
        $ultimoId = 0;
        $this->mensajes = [];                       
        
        $this->usuario = request()->query('usuario', $this->usuario) ?? "";                   
    }

    public function  mensajeRecibido($data)
    {        
        $this->actualizarMensajes($data);
    }

    //Este listener setea el usuario
    public function cambioUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    public function actualizarMensajes($data)
    {                
        if($this->usuario != "")
        {
            // El contenido de la Push
            //$data = \json_decode(\json_encode($data));
            
            //se obtienen los 5 mensajes más recientes
            $mensajes = \App\Chat::orderBy("created_at", "desc")->take(5)->get();
            //$this->mensajes = [];            

            foreach($mensajes as $mensaje)
            {
                if($this->ultimoId < $mensaje->id) // se comprueba si el mensaje estaba antes en el array
                {
                    $this->ultimoId = $mensaje->id;
                    
                    $item = [
                        "id" => $mensaje->id,
                        "usuario" => $mensaje->usuario,
                        "mensaje" => $mensaje->mensaje,
                        "recibido" => ($mensaje->usuario != $this->usuario),
                        "fecha" => $mensaje->created_at->diffForHumans()
                    ];
    
                    array_unshift($this->mensajes, $item);   // se empuja el elemento al inicio del array             
                    //array_push($this->mensajes, $item);                
                }
                
            }

            if(count($this->mensajes) > 5)
            {
                array_pop($this->mensajes);
            }
        }
        else
        {            
            $this->emit('solicitaUsuario');
        }
    }

    public function resetMensajes()
    {
        $this->mensajes = [];
        $this->actualizarMensajes();
    }

    public function dydrate()
    {
        if($this->usuario == "")
        {
            // Le pedimos el usuario al otro componente
            $this->emit('solicitaUsuario');
        }
    }

    public function render()
    {        
        return view('livewire.chat-list');
    }
}
