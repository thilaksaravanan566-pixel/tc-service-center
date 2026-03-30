<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        return view('customer.chat');
    }

    public function message(Request $request)
    {
        $message = strtolower($request->input('message', ''));
        
        $reply = 'I am Thambu AI. I can help you with your services, status tracking, or finding the right spare parts. How can I assist you today?';

        if (str_contains($message, 'status') || str_contains($message, 'track')) {
            $reply = 'You can track your order status live by going to the "My Repairs" tab on your dashboard and clicking the track icon next to your recent repair!';
        } elseif (str_contains($message, 'book') || str_contains($message, 'repair')) {
            $reply = 'You can book a new repair by clicking the \'Book Service\' button on the top right of your dashboard, or by navigating to \'Book Service\' in the sidebar. We even offer pickup and delivery!';
        } elseif (str_contains($message, 'price') || str_contains($message, 'cost') || str_contains($message, 'how much')) {
            $reply = 'Our prices are fully transparent. Diagnostics are free if you proceed with the repair! You can check specific spare part costs in our Shop tab.';
        } elseif (str_contains($message, 'hello') || str_contains($message, 'hi')) {
            $reply = 'Hello! Welcome to Thambu Computers. How can I assist you with your tech today?';
        } elseif (str_contains($message, 'thank')) {
            $reply = 'You are very welcome! If you need anything else, just let me know.';
        }

        // Simulate thinking delay for realism
        usleep(700000); 

        return response()->json([
            'reply' => $reply
        ]);
    }
}
