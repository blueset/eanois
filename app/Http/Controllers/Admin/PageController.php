<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Page;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = Page::paginate(20);
        return view('pages.index', ['pages' => $pages]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.new');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->slug = empty($request->slug) ? SlugHelper::getNextAvailableSlug($request->title, Page::class) : $request->slug;

        $this->validate($request, [
            'title' => 'required',
            'slug' => 'required',
            'data' => 'json'
        ]);

        $page = new Page();
        $page->title = $request->title;
        $page->slug = $request->slug;
        $page->data = $request->data;
        $page->body = $request->body;
        $page->template = $request->template;
        $page->published_on = empty($request->published_on) ? Carbon::now() : Carbon::createFromFormat("Y-m-d H:i:s", $request->published_on);

        $page->save();

        $request->session()->flash("message_success", "Page created.");

        return redirect()->action('Admin\PageController@edit', ['id' => $page->id]);
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
        $page = Page::findOrFail($id);
        return view('pages.edit', ['page' => $page]);
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
        $page = Page::findOrFail($id);

        $this->validate($request, [
            'title' => 'required',
            'slug' => 'required',
            'data' => 'json'
        ]);

        $page->title = $request->title;
        $page->slug = $request->slug;
        $page->data = $request->data;
        $page->body = $request->body;
        $page->template = $request->template;
        $page->published_on = empty($request->published_on) ? Carbon::now() : Carbon::createFromFormat("Y-m-d H:i:s", $request->published_on);

        $page->save();

        $request->session()->flash("message_success", "Page created.");

        return redirect()->action('Admin\PageController@edit', ['id' => $page->id]);
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
        $page = Page::findOrFail($id);
        $page->delete();
        $request->session()->flash("message_success", "Page \"$page->title\" has been deleted.");
        return redirect()->action('Admin\PageController@index');
    }
}
