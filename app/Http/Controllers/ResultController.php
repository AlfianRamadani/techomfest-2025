<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResultController extends Controller
{
    public static function resultIndex()
    {
        return view('result');
    }
}
