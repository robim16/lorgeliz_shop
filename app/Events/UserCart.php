<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

// class UserCart implements ShouldBroadcast
class UserCart implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var
     */
    public $cart;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($cart)
    {
        $this->cart = $cart;
        Log::info('Evento del carrito recibido' . json_encode($cart));
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('cart-updated.' . auth()->id());
        // return ['private-cart-updated.'.auth()->id(), 'cart-updated'];
    }

    public function broadcastAs()
    {
        return 'new-product-to-cart';
    }
}
