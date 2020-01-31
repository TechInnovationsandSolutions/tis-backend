<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $a = ['mark', 'cross', 'ebere'];
        return view('welcome', compact('a'));
    }
}
