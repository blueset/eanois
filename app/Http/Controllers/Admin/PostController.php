<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\SlugHelper;
use App\Post;
use App\PostLink;
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
        $cats = \App\Category::all();
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

        $slug = empty($request->slug) ? SlugHelper::getSlug($request->title) : $request->slug;

        $this->validate($request, [
            'title' => 'required',
            'slug' => 'required',
            'category' => 'required|exists:categories,id',
        ]);

        $post = new Post();
        $post->title = $request->title;
        $post->category = $request->category;
        $post->slug = SlugHelper::getNextAvailableSlug($slug, Post::class);
        $post->desc = $request->desc;
        $post->body = $request->body;
        $post->published_on = empty($request->published_on) ? Carbon::now() : Carbon::createFromFormat("Y-m-d H:i:s", $request->published_on);

        echo 'stage 1';

        $post->save();
        if (isset($request->tags)){
            foreach($request->tags as $t) {
                if (Tag::where('slug', $t)->exists()) {
                    $tag = Tag::where('slug', $t);
                } else {
                    $tag = new Tag();
                    $tag->name = $t;
                    $tag->slug = SlugHelper::getNextAvailableSlug($t, Tag::class);
                    $tag->save();
                }
                $post->tags()->save($tag);
            }
        }

        echo 'stage 2';

        if (isset($request->postlink_name)){
            for ($i = 0; $i < count($request->postlink_name); $i++) {
                if (!empty($request->postlink_name[$i])){
                    $link = new PostLink();
                    $link->name = $request->postlink_name[$i];
                    $link->url = $request->postlink_link[$i];
                    $link->order = $i;
                    $post->links()->save($link);
                }
            }
        }

        echo 'stage 3';

        if (isset($request->postmeta_key)){
            for ($i = 0; $i < count($request->postmeta_key); $i++) {
                if (!empty($request->postmeta_key[$i])){
                    $link = new PostMeta();
                    $link->name = $request->postmeta_key[$i];
                    $link->url = $request->postmeta_value[$i];
                    $post->meta()->save($link);
                }
            }
        }

        echo 'stage 4';

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
        $cats = \App\Category::all();
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
