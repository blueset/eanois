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
            $r['published_on'] = strtotime($r['published_on']);
            $r['category'] = \App\Category::findOrFail(intval($r['category']))->slug;
        }
        $feed_url = explode(" ", \App\Setting::getConfig("feed_url"));
        foreach ($feed_url as $url){
            $reader = new \Reader;
            $resource = $reader->download($url);
            $parser = $reader->getParser($resource->getUrl(), $resource->getContent(), $resource->getEncoding());
            $feed = $parser->execute();
            foreach (array_slice($feed->items, 0, 3) as $item){
                array_push($result, ["title"=>$item->getTitle(), "published_on"=>$item->getDate()->getTimestamp(), "url"=>$item->getUrl(), "type"=>"feed"]);
            }

        }
        usort($result, function($a, $b){return $b["published_on"] - $a["published_on"];});
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
        $column = "id";
        if (isset($request->slug)){
            $request->posts = [$request->slug];
            $column = 'slug';
        }
        $result = [];
        $with = [];
        $select = isset($request->select)? $request->select :
                  ['id', 'slug', 'title', 'published_on', 'desc', 'image',
                   'category', 'tags', 'links', 'meta'];
        if (count($request->posts) == 1){
            $select = ['id', 'slug', 'title', 'body', 'published_on', 'desc', 'image',
                'category', 'tags', 'links', 'meta'];
        }
        if (in_array('category', $select)){
            $with['cate'] = function($query) {$query->select(['id', 'name', 'slug', 'template']);};
        }
        if (in_array('tags', $select)){
            $select = array_diff($select, ['tags']);
            $with['tags'] = function($query) {$query->select(['name', 'slug']);};
        }
        if (in_array('links', $select)){
            $select = array_diff($select, ['links']);
            $with['links'] = function($query) {$query->select(['post', 'name', 'url', 'css_class', 'order']);};
        }
        if (in_array('meta', $select)){
            $select = array_diff($select, ['meta']);
            $with['meta'] = function($query) {$query->select(['post', 'key', 'value']);};
        }
        foreach ($request->posts as $id) {
            $q = \App\Post::with($with)
                ->select($select)
                ->where($column, $id)
                ->firstOrFail();
            $item = $q->toArray();
            if (array_key_exists('image', $item)) {
                $item['imageMeta'] = ["html" => \App\Image::where('slug', $item['image'])->exists() ? \App\Image::where('slug', $item['image'])->first()->pictureElement()->title($item['title'])->render() : ""];
                if (\App\Image::where('slug', $item['image'])->exists()) {
                    $dimension = getimagesize(\Storage::getDriver()->getAdapter()->applyPathPrefix(\App\Image::where('slug', $item['image'])->first()->path));
                    $item['imageMeta']['width'] = $dimension[0];
                    $item['imageMeta']['height'] = $dimension[1];
                } else {
                    $item['imageMeta'] = [];
                }
            }
            if (array_key_exists('desc', $item)) {
                $item['desc'] = \Markdown::text($item['desc']);
            }
            if (array_key_exists('body', $item)) {
                $item['body'] = \Markdown::text($item['body']);
            }
            if (array_key_exists('meta', $item)) {
                $item['meta'] = array_combine(array_column($item['meta'], 'key'), array_column($item['meta'], 'value'));
            }
            array_push($result, $item);
        }
        return response()->json($result);
    }

    public function getPage($slug) {
        $page = \App\Page::where('slug', $slug)->select(["title", "body", "slug", "data", "template"])->firstOrFail()->toArray();
        $page['body'] = \Markdown::text($page['body']);
        $page['data'] = json_decode($page['data']);
        return response()->json($page);
    }

    public function getLinks() {
        return response()->json(\App\Link::orderBy("sort_index")->select([
            "name", "desc", "url", "sort_index", "type"
        ])->get()->toArray());
    }

    private function sitemapUpdateTime($type) {
        if ($type == "posts"){
            return \App\Post::select("published_on")->orderBy("published_on")->first()->published_on;
        }
    }

    public function getSitemap() {
        $conf = json_decode(file_get_contents(public_path(\Theme::url("config.json"))), true);
//        "sitemap": {
//            "fixed": [
//      {
//          "url": "/",
//        "update-time": "posts",
//        "change-freq": "daily",
//        "priority": 10
//      }
//    ],
//    "assoc-pages": {
//                "about": {
//                    "url": "/about",
//        "update-time": "",
//        "change-freq": "monthly",
//        "priority": "3"
//      }
//    },
//    "assoc-posts": {},
//    "generic": {
//                "categories-list": {
//                    "url": "/works",
//        "update-time": "",
//        "change-freq": "monthly",
//        "priority": "3"
//      },
//      "tags-list": null,
//      "links-list": {
//                    "url": "/links",
//        "update-time": "",
//        "change-freq": "monthly",
//        "priority": "1"
//      },
//      "category": {
//                    "url": "/works/{category}",
//        "update-time": "",
//        "change-freq": "daily",
//        "priority": "1"
//      },
//      "post": {
//                    "url": "/works/{category}/{slug}",
//        "update-time": "",
//        "change-freq": "monthly",
//        "priority": "1",
//        "except": {
//                        "category-template": "entry-template"
//        }
//      },
//      "page": null
//    }
//  }
        foreach ($conf['sitemap']['fixed'] as $page) {
            \Sitemap::addTag($page['url'], $this->sitemapUpdateTime($page['update-time']), $page['change-freq'], $page['priority']);
        }
        foreach ($conf['sitemap']['assoc-pages'] as $key => $page) {
            $updateTime = \App\Page::where("slug", $key)->select("published_on")->first()->published_on;
            \Sitemap::addTag($page['url'], $updateTime, $page['change-freq'], $page['priority']);
        }
        foreach ($conf['sitemap']['assoc-posts'] as $key => $page) {
            $updateTime = \App\Post::where("slug", $key)->select("published_on")->first()->published_on;
            \Sitemap::addTag($page['url'], $updateTime, $page['change-freq'], $page['priority']);
        }
        if ($conf['sitemap']['generic']['categories-list']) {
            $page = $conf['sitemap']['generic']['categories-list'];
            \Sitemap::addTag($page['url'], $this->sitemapUpdateTime("categories"), $page['change-freq'], $page['priority']);
        }
        if ($conf['sitemap']['generic']['tags-list']) {
            $page = $conf['sitemap']['generic']['tags-list'];
            \Sitemap::addTag($page['url'], $this->sitemapUpdateTime("tags"), $page['change-freq'], $page['priority']);
        }
        if ($conf['sitemap']['generic']['links-list']) {
            $page = $conf['sitemap']['generic']['links-list'];
            \Sitemap::addTag($page['url'], $this->sitemapUpdateTime("links"), $page['change-freq'], $page['priority']);
        }
        if ($conf['sitemap']['generic']['category']) {
            $page = $conf['sitemap']['generic']['category'];
            $cats = \App\Category::select(["id", "slug"])->get();
            foreach($cats as $cat){
                $url = str_replace("{category}", $cat->slug, $page['url']);
                $updateTime = $cat->posts()->select(["published_on", "category"])->orderBy("published_on")->first()->published_on;
                \Sitemap::addTag($url, $updateTime, $page['change-freq'], $page['priority']);
            }
        }
        if ($conf['sitemap']['generic']['tags-list']) {
            // TODO: Tags list
        }
        if ($conf['sitemap']['generic']['post']) {
            $page = $conf['sitemap']['generic']['post'];
            $ps = \App\Post::select(["category", "slug"])->get();
            foreach($ps as $p){
                $category = $p->cate()->select(["slug", "id"])->first()->slug;
                $url = str_replace(["{category}", "{slug}"], [$category, $p->slug], $page['url']);
                $updateTime = $p->published_on;
                \Sitemap::addTag($url, $updateTime, $page['change-freq'], $page['priority']);
            }
        }
        if ($conf['sitemap']['generic']['page']) {
            $page = $conf['sitemap']['generic']['page'];
            $ps = \App\Page::select(["id", "slug", "published_on"])->get();
            foreach($ps as $p){
                $url = str_replace("{slug}", $p->slug, $page['url']);
                $updateTime = $p->published_on;
                \Sitemap::addTag($url, $updateTime, $page['change-freq'], $page['priority']);
            }
        }
        return \Sitemap::render();
    }
}
