<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function sendTelegramNotification($product)
    {
        $product = [
            'name' => $product->name,
            'description' => $product->description,
            'quantity' => $product->quantity,
            'price' => $product->price,
        ];

    
    } 
}