<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('App.Cliente.{id}', function ($user, $id) {
    return (int) $user->cliente->id === (int) $id;
});

Broadcast::channel('cart-updated.{user_id}', function ($user, $user_id) {
    // Auth::check();
	return (int) $user->id === (int) $user_id; //evento para escuchar cuando se actualiza el carrito
});



