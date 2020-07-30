<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use Cart;

class CartController extends Controller
{
    public function index()
    {
        Cart::clearCartConditions();

        $condition = new \Darryldecode\Cart\CartCondition(array(
            'name' => 'VAT (10.5%)',
            'type' => 'tax',
            'target' => 'total',
            'value' => '10.5%',
            'attributes' => []
        ));

        Cart::condition($condition);

        $itemsContent  = Cart::getContent();
        $subTotal      = Cart::getSubTotal();
        $condition     = Cart::getCondition('VAT (10.5%)');
        $total         = Cart::getTotal();
        $totalQuantity = Cart::getTotalQuantity();

        $itemCount     = $itemsContent->count();

        $tax = $condition->getCalculatedValue($subTotal);

        return view('cart', [
            'itemCount' => $itemCount,
            'itemQuantity' => $totalQuantity,
            'items' => $itemsContent,
            'taxName' => $condition->getName(),
            'taxValue' => $tax,
            'subtotal' => $subTotal,
            'total' => $total
        ]);
    }

    /**
     * Add item to c art
     * @param Request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = Product::find($request->id);

        Cart::add(array(
            'id' => $request->id,
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => 1,
            'attributes' => array(),
            'associatedModel' => $product
        ));

        return redirect()->route('cart')->with('success_message', 'Item was added to your cart!');
    }

    /**
     * Remove item from cart
     * @param id
     * @return void
     */
    public function destroy($id)
    {
        \Cart::remove($id);

        return back()->with('success_message', 'Item has ben removed from cart!');
    }

    /**
     * Update cart item qty
     *
     * @return void
     * @return void
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        Cart::update($id,[
            'quantity' => [
                'relative' => false,
                'value' => $request->qty
            ]
        ]);

        redirect()->route('cart')->with('success_message', 'Item has ben updated!');

        return response($product->qty, 200);
    }
}
