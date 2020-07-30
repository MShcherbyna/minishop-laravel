<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CheckoutRequest;
use App\Order;
use App\OrderProduct;
use App\Product;
use Cart;

class CheckoutController extends Controller
{
    public function index()
    {
        $itemsContent = Cart::getContent();

        if ($itemsContent->count() == 0 ) {
            return redirect()->route('home');
        }

        if (auth()->user() && request()->is('guest-checkout')) {
            return redirect()->route('checkout');
        }

        $subTotal      = Cart::getSubTotal();
        $condition     = Cart::getCondition('VAT (10.5%)');
        $total         = Cart::getTotal();
        $totalQuantity = Cart::getTotalQuantity();

        $itemCount     = $itemsContent->count();

        $tax = $condition->getCalculatedValue($subTotal);

        return view('checkout', [
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
     * Create oreder
     * @param Request $request
     * @return void
     */
    public function store(CheckoutRequest $request)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $content = Cart::getContent()->map(function($item){
            return $item->associatedModel->sku . ', ' . $item->quantity;
        })->values()->toJson();

        try {
            \Stripe\Charge::create([
                'amount' => round(\Cart::getTotal() * 100),
                'currency' => 'usd',
                'source' => $request->stripeToken,
                'description' => 'Order',
                'receipt_email' => $request->email,
                'metadata' => [
                    'items' => $content,
                    'qty' => \Cart::getContent()->count()
                ]
            ]);
        } catch(\Stripe\Exception\CardException $e) {
            $this->createOrder($request, $e->getMessage());

            return back()->withErrors('Error! ' . $e->getMessage());
        } catch (Exception $e) {
            $this->createOrder($request, $e->getMessage());

            return back()->withErrors('Error! ' . $e->getMessage());
        }

        //Add order to DB, orders table
        $order = $this->createOrder($request, null);

        \Cart::clearCartConditions();
        \Cart::clear();

        return redirect()->route('success')->with('success_message', 'Thank you for Your Order!');
    }

    /**
     * Insert order to DB, orders table
     *
     * @param Request $request
     * @param \Stripe\Exception\CardException $error
     * @return array
     */
    protected function createOrder($request, $error)
    {
        // Insert into orders table
        $subTotal = \Cart::getSubTotal();

        $order = Order::create([
            'user_id' => auth()->user() ? auth()->user()->id : null,
            'billing_email' => $request->email,
            'billing_name' => $request->name,
            'billing_address' => $request->address,
            'billing_city' => $request->city,
            'billing_province' => $request->province,
            'billing_postalcode' => $request->postalcode,
            'billing_phone' => $request->phone,
            'billing_name_on_card' => $request->name_on_card,
            'billing_subtotal' => $subTotal,
            'billing_tax' => round(Cart::getCondition('VAT (10.5%)')->getCalculatedValue($subTotal), 2),
            'billing_total' => round(Cart::getTotal(), 2),
            'error' => $error,
        ]);

        // Insert into order_product table
        foreach (Cart::getContent() as $item) {
            OrderProduct::create([
                'order_id' => $order->id,
                'product_id' => $item->associatedModel->id,
                'qty' => $item->quantity,
            ]);

            Product::find($item->associatedModel->id)->decrement('qty', $item->quantity);
        }

        return $order;
    }
}
