<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Newsletter\Newsletter;

class NewsLetterController extends Controller
{
    public function subscribe(Request $request)
    {
        if (!Newsletter::isSubscribed($request->email)) {
            Newsletter::subscribePending($request->email);
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Thank you for subscribing',
                //'data' => Message::collection(AppMessage::all()),
            ], 200);  
        }
        return response()->json([
                'status' => 'error',
                'code' => 401,
                'message' => 'Subscription failed',
                //'data' => Message::collection(AppMessage::all()),
            ], 401);  
    }
}
