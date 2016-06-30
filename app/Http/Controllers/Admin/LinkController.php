<?php

namespace App\Http\Controllers\Admin;

use App\Link;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests;

class LinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $links = Link::all()->sortBy('sort_order');
        return view('links.index', ['links' => $links]);
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
        $this->validate($request, [
            'name' => 'required',
            'url' => 'required_if:type,link|url',
            'type' => 'required|in:link,divider',
            'sort_index' => 'integer'
        ]);
        $link = new Link([
            'name' => $request->name,
            'desc' => $request->desc,
            'url' => $request->url,
            'sort_index' => $request->sort_index,
            'type' => $request->type
        ]);
        $link->save();
        $request->session()->flash("message_success", "Link added.");
        return redirect()->action('Admin\LinkController@index');
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
        $link = Link::findOrFail($id);
        $this->validate($request, [
            'name' => 'required',
            'url' => 'required_if:type,link|url',
            'type' => 'required|in:link,divider',
            'sort_index' => 'integer'
        ]);
        $link->name = $request->name;
        $link->desc = $request->desc;
        $link->url = $request->url;
        $link->sort_index = $request->sort_index;
        $link->type = $request->type;
        $link->save();
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
        $link = Link::findOrFail($id);
        $link->delete();
        $request->session()->flash("message_success", "Link $id has been deleted.");
        return "Link $id has been deleted.";
    }
}
