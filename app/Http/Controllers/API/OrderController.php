<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\API\Book;
use App\Models\API\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'cart_items' => 'required|array',
            'cartItems.*.bookId' => 'required|exists:books,id',
            'cartItems.*.quantity' => 'required|integer|min:1',
            'shipping_address' => 'required|string',
            'payment_info' => 'required|string',
        ]);

        $userId = $request->input('user_id');
        $cartItemsData = $request->input('cart_items');
        $shippingAddress = $request->input('shipping_address');
        $paymentInfo = $request->input('payment_info');

        \DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id' => $userId,
                'shipping_address' => $shippingAddress,
                'payment_info' => $paymentInfo,
            ]);

            foreach ($cartItemsData as $cartItemData) {
                $bookId = $cartItemData['book_id'];
                $quantity = $cartItemData['quantity'];

                // Retrieve the book and check if there's enough inventory
                $book = Book::findOrFail($bookId);
                if ($book->inventory < $quantity) {
                    \DB::rollBack();

                    return response()->json(['error' => 'Not enough inventory for the selected books.'], 400);
                }

                // Deduct the inventory
                $book->decrement('inventory', $quantity);

                $order->books()->attach($bookId, ['quantity' => $quantity]);
            }
            \DB::commit();

            return response()->json(['message' => 'Order processed successfully', 'order' => $order]);

        } catch (\Exception $e) {
            \DB::rollBack();

            \Log::error($e);

            return response()->json(['error' => 'An error occurred while processing the order.'], 500);
        }
    }
}
