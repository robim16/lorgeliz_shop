<?php

namespace App\Helpers;

use App\Carrito;
use App\CarritoProducto;
use Illuminate\Support\Facades\Cookie;

class Cart {

    public static function getCartItemsCount()
    {
       
        $user = auth()->user();

        if (!$user) {
            
           $cartItems = self::getCookieCartItems();
    
            return array_reduce(
                $cartItems,
                function($carry, $item){
                    return $carry + $item['quantity'];
                },0
            );
        }
        
    }


    public static function getCookieCartItems()
    {
        $request = \request();

        return json_decode($request->cookie('cart_items', '[]'), true);
    }


    public static function moveCartItemsIntoDb()
    {
        $request = \request();
        $cartItems = self::getCookieCartItems();

        $carrito =  Carrito::estado()->cliente(auth()->user()->cliente->id)
            ->first();
    
    
        $dbCartItems = $carrito->carritoProductos->keyBy('producto_referencia_id');

        $newCartItems = [];
        foreach ($cartItems as $cartItem) {

            if (isset($dbCartItems[$cartItem['product_id']])) {
                $quantity_cart = $dbCartItems[$cartItem['product_id']]['cantidad'];
                $quantity = $cartItem['quantity'];
                $new_quantity = $quantity_cart + $quantity;
                $dbCartItems[$cartItem['product_id']]->update(['cantidad' => $new_quantity]);
            }
            else{

                $newCartItems[] = [
                    'carrito_id' => $carrito->id,
                    'producto_referencia_id' => $cartItem['product_id'],
                    'cantidad' => $cartItem['quantity']
                ];
            }
        }

        if (!empty($newCartItems)) {
            CarritoProducto::insert($newCartItems);
        }

        Cookie::queue(Cookie::forget('cart_items'));

    }

}