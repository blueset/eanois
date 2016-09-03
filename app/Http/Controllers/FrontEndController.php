<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Setting;

class FrontEndController extends Controller
{
    public function index() {
        $setting = \App\Setting::getConfig();
        $setting['site_logo_url'] = route("AdminImageControllerShow", $setting['site_logo']);
        return view('index', ["setting" => $setting]);
    }
}
