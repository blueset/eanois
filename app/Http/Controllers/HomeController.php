<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use PicoFeed\Syndication\AtomFeedBuilder;
use PicoFeed\Syndication\AtomItemBuilder;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
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

    private function sitemapUpdateTime($type) {
        if ($type == "posts"){
            return \App\Post::select("published_on")->orderBy("published_on")->first()->published_on;
        }
    }

    public function getSitemap() {
        $conf = json_decode(file_get_contents(public_path(\Theme::url("config.json"))), true);
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

    public function getAtomFeed() {
        $feedBuilder = AtomFeedBuilder::create()
            ->withTitle(\App\Setting::getConfig("site_name"))
            ->withSiteUrl(url("/"))
            ->withFeedUrl(action("HomeController@getAtomFeed"))
            ->withDate(new \DateTime());
        $post_url_template = json_decode(file_get_contents(public_path(\Theme::url("config.json"))), true)['sitemap']['generic']['post']['url'];
        $posts = \App\Post::select(["title", "category", "slug", "desc", "body", "published_on", "updated_on", "image"])->take(15)->get();

        foreach ($posts as $post){
            $category = $post->cate()->select(["slug", "id"])->first()->slug;
            $url = str_replace(["{category}", "{slug}"], [$category, $post->slug], $post_url_template);
            $image = "";
            if(!empty($post->image)){
                $image = \App\Image::where("slug", $post->image)->first()->pictureElement()->render();
            }
            $desc = $image . \Markdown::text($post->desc);
            $body = $image . $desc . \Markdown::text($post->body);
            $item = AtomItemBuilder::create($feedBuilder)
                ->withTitle($post->title)
                ->withUrl(url($url))
                ->withPublishedDate(new \DateTime($post->published_on))
                ->withUpdatedDate(new \DateTime($post->updated_on))
                ->withSummary($desc)
                ->withContent($body);
            $feedBuilder = $feedBuilder->withItem($item);
        }
        $result = $feedBuilder->build();
        return response($result)->header("Content-Type", "application/xml");
    }
}
