<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\NemoController;
use App\Models\Airport;

class PageController extends Controller
{
    public function welcome()
    {
        return view('welcome',[
            'data'=>''
        ]);
    }
}
