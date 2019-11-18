<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');

        //Log::channel('single')->info('Constructor of TestController');
    }
}
