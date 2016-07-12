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

    public function getLastUpdate(Request $request) {
        if (!$request->wantsJson()) {
            return response("Only JSON is accepted.", 406);
        }
        $result = \App\Post::select(["title", "category", "slug", "published_on"])->take(3)->get()->toArray();
        foreach ($result as &$r) {
            $r['type'] = 'post';
            $r['category'] = \App\Category::findOrFail(intval($r['category']))->slug;
        }
        return response()->json(array_slice($result, 0, 3));
    }
}
