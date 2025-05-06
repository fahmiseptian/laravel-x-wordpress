<?php

namespace App\Http\Controllers;

use App\Lib\Wordpress;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;

class ThemeController extends BaseController
{
    public function index()
    {
        return view('theme.index');
    }
}
