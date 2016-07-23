<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Helpers\SlugHelper;
use App\Post;
use App\PostLink;
use App\PostMeta;
use App\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PostController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = \App\Post::paginate(20);
        return view('posts.index', ['posts' => $posts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cats = Category::all();
        return view('posts.new', ['categories' => $cats]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->slug = empty($request->slug) ? SlugHelper::getNextAvailableSlug($request->title, Post::class) : $request->slug;

        $this->validate($request, [
            'title' => 'required',
            'slug' => 'required',
            'category' => 'required|exists:categories,id',
            'image' => 'exists:images,slug,deleted_at,NULL'
        ]);

        $post = new Post();
        $post->title = $request->title;
        $post->category = $request->category;
        $post->slug = $request->slug;
        $post->desc = $request->desc;
        $post->body = $request->body;
        $post->image = $request->image;
        $post->published_on = empty($request->published_on) ? Carbon::now() : Carbon::createFromFormat("Y-m-d H:i:s", $request->published_on);

        $post->save();
        if (isset($request->tags)){
            foreach($request->tags as $t) {
                if (Tag::where('slug', $t)->exists()) {
                    $tag = Tag::where('slug', $t);
                } else {
                    $tag = new Tag();
                    $tag->name = $t;
                    $tag->slug = SlugHelper::getNextAvailableSlug($t, Tag::class);
                }
                $post->tags()->save($tag);
            }
        }

        if (isset($request->postlink)){
            foreach ($request->postlink as $order => $value){
                if (!empty($value->name)){
                    $link = new PostLink();
                    $link->name = $value->name;
                    $link->url = $value->url;
                    $link->order = $order;
                    $post->links()->save($link);
                }
            }
        }

        if (isset($request->postmeta)){
            foreach ($request->postmeta as $item){
                if (!empty($item->key)){
                    $link = new PostMeta();
                    $link->key = $item->key;
                    $link->value = $item->value;
                    $post->meta()->save($link);
                }
            }
        }

        $post->save();
        $request->session()->flash("message_success", "Post created.");

        return redirect()->action('Admin\PostController@edit', ['id' => $post->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::findOrFail($id);
        $cats = Category::all();
        return view('posts.edit', ['post' => $post, 'categories' => $cats]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $this->validate($request, [
            'title' => 'required',
            'slug' => 'required|unique:posts,slug,'.$id,
            'category' => 'required|exists:categories,id',
            'image' => 'exists:images,slug,deleted_at,NULL'
        ]);

        $post->title = $request->title;
        $post->category = $request->category;
        $post->slug = $request->slug;
        $post->desc = $request->desc;
        $post->body = $request->body;
        $post->image = $request->image;
        $post->published_on = empty($request->published_on) ? Carbon::now() : Carbon::createFromFormat("Y-m-d H:i:s", $request->published_on);

        $post->save();
        if (isset($request->tags)){
            $tagIds = [];
            foreach($request->tags as $t) {
                if (Tag::where('slug', $t)->exists()) {
                    array_push($tagIds, Tag::where('slug', $t)->firstOrFail()->id);
                } else {
                    $tag = new Tag();
                    $tag->name = $t;
                    $tag->slug = SlugHelper::getNextAvailableSlug($t, Tag::class);
                    $tag->save();
                    array_push($tagIds, $tag->id);
                }
                $post->tags()->sync($tagIds);
            }
        }

        if (!isset($request->postlink)){
            $request->postlink = [];
        }
        $orders = array_column($post->links()->get()->toArray(), 'order');
        foreach ($orders as $order) {
            if (!array_key_exists($order, $request->postlink)){
                $post->links()->where("order", $order)->first()->delete();
            }
        }
        foreach ($request->postlink as $i => $item) {
            if (!empty($item['name'])){
                if (in_array($i, $orders)) {
                    $link = $post->links()->where("order", $i)->first();
                    $link->name = $item['name'];
                    $link->url = $item['url'];
                    $link->save();
                } else {
                    $link = new PostLink();
                    $link->name = $item['name'];
                    $link->url = $item['url'];
                    $link->order = $i;
                    $post->links()->save($link);
                }
            }
        }


        if (!isset($request->postmeta)) {
            $request->postmeta = [];
        }
            $keys = array_column($post->meta()->get()->toArray(), 'key');
            $new_keys = array_column($request->postmeta, 'key');
            foreach ($keys as $key) {
                if (!array_key_exists($key, $new_keys)){
                    $post->meta()->where("key", $key)->first()->delete();
                }
            }
            foreach ($request->postmeta as $i => $item) {
                if (!empty($item['key'])){
                    if (in_array($i, $keys)) {
                        $meta = $post->meta()->where("order", $i)->first();
                        $meta->key = $item['key'];
                        $meta->value = $item['value'];
                        $meta->save();
                    } else {
                        $meta = new PostMeta();
                        $meta->key = $item['key'];
                        $meta->value = $item['value'];
                        $post->meta()->save($meta);
                    }
                }
            }


        $post->save();
        $request->session()->flash("message_success", "Post edited.");

        return redirect()->action('Admin\PostController@edit', ['id' => $post->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param  int    $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        $request->session()->flash("message_success", "Post \"$post->title\" has been deleted.");
        return redirect()->action('Admin\PostController@index');
    }

    /**
     * Bulk update/remove entries in Post.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function bulkUpdate(Request $request) {
        $this->validate($request, [
            'id.*' => 'required|exists:posts,id,deleted_at,NULL',
            'action' => 'required|in:delete,moveToCategory',
            'category_id' => 'required_if:action,moveToCategory|exists:categories,id,deleted_at,NULL'
        ]);
        if ($request->action == "delete") {
            foreach ($request->id as $i) {
                Post::find($i)->delete();
            }
            $request->session()->flash("message_success", "Selected posts has been removed.");
            return "Selected posts has been removed";
        } elseif ($request->action == "moveToCategory") {
            foreach ($request->id as $i) {
                $post_i = Post::find($i);
                $post_i->category = $request->category_id;
                $post_i->save();
            }
            $cat_name = Category::find($request->category_id)->name;
            $request->session()->flash("message_success", "Selected posts has been moved to $cat_name.");
            return "Selected posts has been moved to $cat_name.";
        }
    }
}




