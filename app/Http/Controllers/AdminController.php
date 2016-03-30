<?php

namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Http\Request;

use App\Http\Requests;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function viewSettings() {
        $config = Setting::getConfig();

        return view('settings', ['config' => $config]);
    }
    public function putSettings(Request $request) {
        Setting::setConfig($request->toArray());
        $request->session()->flash("message_success", "Settings updated.");
        return redirect()->action('AdminController@viewSettings');
    }
}
