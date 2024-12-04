<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Helpers\Cart;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserLoggedInListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Login'  $event
     * @return void
     */
    public function handle(Login $event)
    {
        Cart::moveCartItemsIntoDb();
    }
}
