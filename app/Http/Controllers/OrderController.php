<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Http\Resources\Order as OrderResource;
use App\Http\Resources\OrderPayment as ResourcesOrderPayment;
use App\Order;
use App\OrderItem;
use App\OrderPayment;
use App\Product;
use App\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function all()
    {
        $orders = Order::with('user', 'address')->get();
        //return ResourcesProduct::collection(Product::with('category')->paginate(10));
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'All Orders Made',
            // 'data' => $cart,
            'data' => OrderResource::collection($orders),
        ], 200);
    }
    public function index()
    {
        $cart = auth()->user()->orders;
        //return ResourcesProduct::collection(Product::with('category')->paginate(10));
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'User\'s Order',
            // 'data' => $cart,
            'data' => OrderResource::collection($cart),
        ], 200);
    }

    public function store(Request $request)
    {

        $request->validate([
            'address_id' => 'required',
            'cart' => 'required'
        ]);

        foreach($request->cart as $item){
            auth()->user()->cart()->updateOrCreate(
                [
                    'product_id' => $item['product_id']
                ],
                [
                    'amount' => $item['amount'],
                    'quantity' => $item['quantity'],
                ]
            );
        }

        $cart = auth()->user()->cart->where('quantity', '>', 0);

        $order = 0;

        if (count($cart)) {

            $amount = 0;
            foreach ($cart as $item) {
                $amount += $item->quantity * $item->amount;
                //$products[] = ['id' => $item->product_id, 'amount' => $item->amount, 'quantity' => $item->quantity];
            }

            //dd($products);

            $order = auth()->user()->orders()->create(
                [
                    'products' => 'none',
                    'address_id' => $request->address_id,
                    'amount' => $amount,
                    'quantity' => 0,
                    'status' => 0,
                ]
            );

             foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'price' => $item->amount,
                    'quantity' => $item->quantity,
                ]);
                $prod = Product::find($item->product_id);
                $prod->update([
                    'quantity' => $prod->quantity - $item->quantity
                ]);
            }

            $pay = $this->initPay($order);



            Cart::whereIn('id', $cart->pluck('id'))->delete();
        }
        return response()->json([
            'status' => 'success',
            'code' => 201,
            'message' => $order ? 'Order created' : 'No order',
            // 'data' => $cart,
            'data' => $pay ? $pay : 'No Items in cart',
        ], 201);
    }

    public function show(Order $id)
    {
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Order Details',
            'data' => new OrderResource($id),
        ], 200);
    }

    public function update(Request $request, Order $id)
    {
        $request->validate([
            'address_id' => 'required',
            'cart' => 'required'
        ]);

        foreach($request->cart as $item){
            auth()->user()->cart()->updateOrCreate(
                [
                    'product_id' => $item['product_id']
                ],
                [
                    'amount' => $item['amount'],
                    'quantity' => $item['quantity'],
                ]
            );
        }

        $cart = auth()->user()->cart->where('quantity', '>', 0);

        $order = 0;

         if (count($cart)) {

            $amount = 0;
            foreach ($cart as $item) {
                $amount += $item->quantity * $item->amount;
                //$products[] = ['id' => $item->product_id, 'amount' => $item->amount, 'quantity' => $item->quantity];
            }

            //dd($products);

            $id->update(
                [
                    'products' => 'none',
                    'address_id' => $request->address_id,
                    'amount' => $amount+$id->amount,
                    'quantity' => 0,
                    'status' => 0,
                ]
            );

             foreach ($cart as $item) {
                OrderItem::updateOrCreate(
                    [
                        'order_id' => $id->id,
                        'product_id' => $item->product_id,
                    ],
                    [
                        'price' => $item->amount,
                        'quantity' => $item->quantity,
                ]);
            }

            $pay = $this->initPay($id);



            Cart::whereIn('id', $cart->pluck('id'))->delete();
        }

        //dd($id->products);
        

        return response()->json([
            'status' => 'success',
            'code' => 201,
            'message' => 'Order updated',
            'data' => $pay ? $pay : 'Order update failed',
        ], 201);
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        if ($order) {
            $order->delete();
        } else {
            return response()->json(['error' => 'Order not found']);
        }

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Order deleted',

        ], 200);
    }

    public function userOrders(User $id)
    {
        $cart = $id->orders;
        //return ResourcesProduct::collection(Product::with('category')->paginate(10));
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'User\'s Orders',
            // 'data' => $cart,
            'data' => OrderResource::collection($cart),
        ], 200);
    }

    public function initPay($order)
    {
        $result = array();
        $timesammp=DATE("dmyHis");
        $sk = env('PS_KEY', 'sk_test_ab2e717da001e28d0138f84822fbff6249b33ab3');
        //Set other parameters as keys in the $postdata array
        $postdata =  array('email' => $order->user->email, 'amount' => $order->amount*100,"reference" => $timesammp);
        $url = "https://api.paystack.co/transaction/initialize";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $headers = [
        'Authorization: Bearer '.$sk,
        'Content-Type: application/json',

        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $request = curl_exec ($ch);

        curl_close ($ch);

        if ($request) {
            $result = json_decode($request, true);
            
            return response()->json($result);

            OrderPayment::create([
                'order_id' => $order->id,
                'access_code' => $result['data']['access_code'],
                'reference' => $result['data']['reference'],
                'amount' => $order->amount,
                'status' => false
            ]);

        }
        return $result;
    }

    public function verifyPay(Request $request)
    {
            $result = array();
            $sk = env('PS_KEY', 'sk_test_ab2e717da001e28d0138f84822fbff6249b33ab3');

        $pay = OrderPayment::with('order')->where('reference', $request->reference)->first();

        if(!$pay){
            return response()->json([
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Payment record not found',
                    // 'data' => $cart,
                    //'data' => new OrderResource($pay->order),
                ], 404);
        }

        //The parameter after verify/ is the transaction reference to be verified
        $url = 'https://api.paystack.co/transaction/verify/'.$request->reference;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt(
        $ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer '.$sk]
        );
        $curl_request = curl_exec($ch);
        curl_close($ch);

        if ($curl_request) {
            $result = json_decode($curl_request, true);
            // print_r($result);
            if($result){
            if($result['data']){
                //something came in
                if($result['data']['status'] == 'success'){
                    $pay->update(['status' => true]);

                // the transaction was successful, you can deliver value
                /* 
                @ also remember that if this was a card transaction, you can store the 
                @ card authorization to enable you charge the customer subsequently. 
                @ The card authorization is in: 
                @ $result['data']['authorization']['authorization_code'];
                @ PS: Store the authorization with this email address used for this transaction. 
                @ The authorization will only work with this particular email.
                @ If the user changes his email on your system, it will be unusable
                */
                return response()->json([
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Transaction was successful',
                    // 'data' => $cart,
                    'data' => new OrderResource($pay->order),
                ], 200);
                echo "Transaction was successful";
                }else{
                // the transaction was not successful, do not deliver value'
                // print_r($result);  //uncomment this line to inspect the result, to check why it failed.
                echo "Transaction was not successful: Last gateway response was: ".$result['data']['gateway_response'];
                }
            }else{
                echo $result['message'];
            }

            }else{
            //print_r($result);
            die("Something went wrong while trying to convert the request variable to json. Uncomment the print_r command to see what is in the result variable.");
            }
        }else{
            //var_dump($request);
            die("Something went wrong while executing curl. Uncomment the var_dump line above this line to see what the issue is. Please check your CURL command to make sure everything is ok");
        }
    }

    public function payments(Request $request)
    {
        if($request->paid == 1){
            $payments = OrderPayment::where('status', true)->get();
            $msg = 'Settled Order Payment Records';
        }elseif($request->paid == 0){
            $payments = OrderPayment::where('status', false)->get();
            $msg = 'Pending Order Payment Records';
        }else{
            $payments = OrderPayment::all();
            $msg = 'All Order Payment Records';
        }
        return response()->json([
                    'status' => 'success',
                    'code' => 200,
                    'message' => $msg,
                    // 'data' => $cart,
                    'data' => ResourcesOrderPayment::collection($payments),
                ], 200);
    }

    public function payment(OrderPayment $id)
    {
        return response()->json([
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Payment Details',
                    // 'data' => $cart,
                    'data' => new ResourcesOrderPayment($id),
                ], 200);
    }


}
