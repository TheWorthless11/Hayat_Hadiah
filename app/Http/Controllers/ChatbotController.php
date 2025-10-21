<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    /**
     * Display the chatbot interface
     */
    public function index()
    {
        return view('chatbot.index');
    }
}
