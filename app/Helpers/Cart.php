<?php

namespace App\Helpers;


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

}