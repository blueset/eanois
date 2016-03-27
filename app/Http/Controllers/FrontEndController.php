<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Config;

class FrontEndController extends Controller
{
    public function index() {
        $config = Config::getConfig('site_name');
        return view('index', ['config' => $config]);
    }
}
