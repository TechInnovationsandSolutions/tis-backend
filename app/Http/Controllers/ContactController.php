<?php

namespace App\Http\Controllers;

use App\Http\Resources\Message;
use App\Message as AppMessage;
use App\Notifications\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ContactController extends Controller
{
    public function index()
    {
         return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Contact Messages',
                'data' => Message::collection(AppMessage::all()),
            ], 200);
    }

    public function send(Request $request)
    {
        $email = env('INFO_EMAIL', 'gyelmis@gmail.com');
        $msg = AppMessage::create($request->validate([
            'subject' => 'required',
            'name' => 'required',
            'email' => 'required',
            'body' => 'required',
        ]));

        Notification::route('mail', 'gyelmis@gmail.com')
            ->notify(new ContactMessage($msg));

           return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Message sent',
                //'data' => Message::collection(AppMessage::all()),
            ], 200);  

    }
}
