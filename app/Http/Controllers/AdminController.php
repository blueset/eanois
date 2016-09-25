<?php

namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Http\Request;

use App\Http\Requests;

class AdminController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    // Settings
    
    public function viewSettings() {
        $config = Setting::getConfig();

        return view('settings', ['config' => $config]);
    }
    public function putSettings(Request $request) {
        Setting::setConfig($request->toArray(), true);
        $request->session()->flash("message_success", "Settings updated.");
        return redirect()->action('AdminController@viewSettings');
    }

    // Themes

    public function themeIndex() {
        $frontList = [];
        $backList = [];
        foreach (config('themes.themes') as $key => $item) {
            if ($item['theme-type'] == 'frontend') {
                array_push($frontList, $key);
            } elseif ($item['theme-type'] == 'backend') {
                array_push($backList, $key);
            }
        };
        $param = [
            'front' => Setting::getConfig('theme'),
            'back' => Setting::getConfig('admin_theme'),
            'frontList' => $frontList,
            'backList' => $backList
        ];
        return view("themes", $param);
    }

    public function themeUpdate(Request $request) {
        if ($request->type == "front"){
            $key = "theme";
        } elseif ($request->type == "back") {
            $key = "admin_theme";
        } else {
            $key = "theme";
        }
        Setting::setConfig([$key => $request->id]);
        $request->session()->flash("message_success", "Theme updated.");
        return "Theme updated.";
    }

    public function mediaIframe(Request $request) {
        $images = \App\Image::all();
        $paras = [
            "default" => [
                "desc" => "Add to description",
                "body" => "Add to body",
                "feat" => "Set as featuring image"
            ],
            "single" => [
                "select" => "Select this image"
            ]
        ];
        $paraskey = $request->input("type", "default");
        return view('frames.media', ['images' => $images, 'paras' => json_encode($paras[$paraskey])]);
    }
}
