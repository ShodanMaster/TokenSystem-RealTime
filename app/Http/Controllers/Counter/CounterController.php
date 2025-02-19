<?php

namespace App\Http\Controllers\Counter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CounterController extends Controller
{
    public function index(){
        return view('counter.index');
    }
}
