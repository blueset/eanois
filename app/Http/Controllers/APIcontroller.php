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

    public function getLastUpdate() {
        $result = \App\Post::select(["title", "category", "slug", "published_on"])->take(3)->get()->toArray();
        foreach ($result as &$r) {
            $r['type'] = 'post';
            $r['category'] = \App\Category::findOrFail(intval($r['category']))->slug;
        }
        return response()->json(array_slice($result, 0, 3));
    }

    public function getAllCategories() {
        $result = \App\Category::select(["slug", "name"])->get()->toArray();
        return response()->json($result);
    }

    public function getCategory($slug) {
        $cat = \App\Category::where('slug', $slug)->select(["id", "slug", "name", "template"])->firstOrFail()->toArray();
        $cat['posts'] = \App\Post::select(['id'])->where("category", $cat['id'])->paginate(20)->toArray();
        return response()->json($cat);
    }

    public function postPosts(Request $request){
        $result = [];
        foreach ($request->posts as $id) {
            $q = \App\Post::with(['tags', 'links', 'meta', 'category'])->where('id', $id)->firstOrFail();
            $item = $q->toArray();
            $item['imageHtml'] = \App\Image::where('slug', $item['image'])->exists() ? \Markdown::text("![" . $item['title'] . "](image:" . $item['image'] . ')') : "";
            $item['desc'] = \Markdown::text($item['desc']);
            $item['body'] = \Markdown::text($item['body']);
            array_push($result, $item);
        }
        return response()->json($result);
    }

}
