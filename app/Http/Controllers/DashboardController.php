<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function productStatistics()
    {
        $totalProducts = Product::count();
        $totalQuantity = Product::sum('quantity');
        $averagePrice = Product::average('price');

        return response()->json([
            'total_products' => $totalProducts,
            'total_quantity' => $totalQuantity,
            'average_price' => $averagePrice,
        ]);
    }
}