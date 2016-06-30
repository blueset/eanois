<?php

namespace App\Http\Controllers;

use App\Helpers\SlugHelper;
use Illuminate\Http\Request;

use App\Http\Requests;

class APIController extends Controller
{
    public function getSlug(Request $request) {
        if (! $request->has('msg')) {
            return response()->json(['error' => 'Field "msg" is required.'], 400);
        }
        if ($request->has('module')) {
            if ($request->has('id')) {
                return SlugHelper::getNextAvailableSlug($request->msg, $request->module, $request->id);
            }
            return SlugHelper::getNextAvailableSlug($request->msg, $request->module);
        }
        return SlugHelper::getSlug($request->msg);
    }
}
