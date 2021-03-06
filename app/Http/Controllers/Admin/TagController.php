<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\SlugHelper;
use App\Tag;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tags = Tag::all()->sortByDesc(function ($tag, $key) {
            return $tag->posts()->count();
        });
        if ($request->ajax()){
            return response()->json($tags->toArray());
        }
        return view('posts.tags', ['tags' => $tags]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->slug = SlugHelper::getNextAvailableSlug($request->slug, Tag::class);
        $this->validate($request, [
            'name' => 'required',
            'slug' => 'unique:categories,slug'
        ]);
        $tag = new Tag;
        $tag->name = $request->name;
        $tag->slug = $request->slug;
        $tag->save();
        $request->session()->flash("message_success", "Tag added.");
        return redirect()->action('Admin\TagController@index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tag = Tag::findOrFail($id);
        return view('posts.tag-single', ['tag' => $tag]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $this->validate($request, [
            'name' => 'required',
            'slug' => 'unique:categories,slug'
        ]);
        $tag = Tag::findOrFail($id);
        $tag->name = $request->name;
        $tag->slug = $request->slug;
        $tag->save();
        $request->session()->flash("message_success", "Tag updated.");
        return redirect()->action('Admin\TagController@show', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $tag = Tag::findOrFail($id);
        $tag->remove();
        $request->session()->flash("message_success", "Tag removed.");
        return redirect()->action('Admin\TagController@index');
    }
}
