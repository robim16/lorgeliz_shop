<?php

namespace App\Helpers;

use App\Carrito;

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

    
        return $dbCartItems = $carrito->carritoProductos->keyBy('producto_referencia_id');

        $newCartItems = [];
        foreach ($cartItems as $cartItem) {
            if (isset($dbCartItems[$cartItem['product_id']])) {
                continue;
            }

            $newCartItems[] = [
                'user_id' => $request->user()->id,
                'product_id' => $cartItem['product_id'],
                'quantity' => $cartItem['quantity']
            ];
        }

        if (!empty($newCartItems)) {
            CartItem::insert($newCartItems);
        }
    }

}