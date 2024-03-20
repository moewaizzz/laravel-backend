<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function addToCart(Request $request){
        $request->validate([
            'recipe_id' => 'required|exists:recipes,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = auth()->user();


        $recipe = Recipe::findOrFail($request->recipe_id);


        $cart = $user->cart;

        if (!$cart) {
            $cart = Cart::create(['user_id' => $user->id]);
        }

        $cartItem = $cart->items()->where('recipe_id', $recipe->id)->first();

        if ($cartItem) {
            // Update the quantity if the product is already in the cart
            $cartItem->update(['quantity'=> $request->quantity]);
        } else {
            // Create a new cart item if the product is not in the cart
            $cartItem = CartItem::create([
                "quantity"=> $request->quantity,
                "recipe_id"=> $recipe->id,
                "cart_id"=> $cart->id,
            ]);
        }


        return response()->json(['message' => 'Product added to cart successfully'], 200);


       
    }

    public function show(Request $request)
    {
        // $user = User::find(1); // Retrieve the user by ID
        // $cart = $user->cart()->first(); // Access the cart relationship using parentheses

        $user = Auth::user();
        $cartItems = $user->cart->items()->with('recipe')->get();
        return response()->json(['data' => $cartItems]);
    }


    public function updateQuantity(Request $request){

        $request->validate([
            'recipe_id' => 'required|exists:recipes,id',
        ]);

        $user = auth()->user();

    // $cartItem = CartItem::where('cart_id', $user->cart->id)
    //     ->where('recipe_id', $request->recipe_id)
    //     ->firstOrFail();

    //     $cartItem->update(['quantity' => $request->quantity]);

    $cartItem = CartItem::where('cart_id', $user->cart->id)
    ->where('recipe_id', $request->recipe_id)
    ->increment('quantity');

        
        
        



        return response()->json(['message' => 'Quantity updated successfully'], 200);
}

public function remove($recipe_id){
   

    $user = auth()->user();

    $cartItem = CartItem::where('cart_id', $user->cart->id)->where('recipe_id', $recipe_id)
    ->firstOrFail()->delete();
    return response()->json(['message' => 'Quantity delete successfully'], 200);
    


} 

}