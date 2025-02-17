<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
    public function list(Request $request)
    {
        $query = Product::query();
    
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }
    
        $products = $query->paginate(10);
    
        return response()->json($products);
    
    }

    private function sendTelegramNotification($product)
    {
        $chatId = env('TELEGRAM_CHAT_ID'); 
        $botToken = env('TELEGRAM_BOT_TOKEN'); 
        $message = "📦 Add new product🙏\n\n" .
                "Name: {$product->name}\n" .
                "Price: {$product->price}\n" .
                "Quantity: {$product->quantity}";

        // Send notification to Telegram API
        Http::withOptions(['verify' => false])->post("https://api.telegram.org/bot{$botToken}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $message,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
        ]);

        $product = Product::create($validated);

        $this->sendTelegramNotification($product);

        return response()->json($product, 201);
    }

    public function show($id)
    {
        return Product::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
        ]);

        $product->update($validated);

        return response()->json($product, 200);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(null, 204);
    }



}