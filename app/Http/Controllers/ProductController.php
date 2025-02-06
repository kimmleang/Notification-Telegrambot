<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->quantity = $request->quantity;
        $product->price = $request->price;
        $product->save();

        // Send notification to Telegram bot
        $this->sendTelegramNotification($product);

        return redirect()->route('products.index')->with('success', 'Product added successfully!');
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        $product = Product::findOrFail($id);
        $product->name = $request->name;
        $product->description = $request->description;
        $product->quantity = $request->quantity;
        $product->price = $request->price;
        $product->save();

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }

    private function sendTelegramNotification($product)
    {
        $chatId = 'e'; // Replace with the Telegram chat ID where the message will be sent
        $telegramApiUrl = "https://api.telegram.org/botYOUR_BOT_TOKEN/sendMessage";

        $message = "A new product has been added to the stock:\n" .
                   "Product Name: {$product->name}\n" .
                   "Quantity: {$product->quantity}\n" .
                   "Price: {$product->price}";

        $params = [
            'chat_id' => $chatId,
            'text' => $message,
        ];

        // Send the request to the Telegram API
        file_get_contents($telegramApiUrl . '?' . http_build_query($params));
    }
}