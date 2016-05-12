<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Helpers\SlugHelper;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = \App\Category::all();
        if ($request->ajax()){
            return response()->json($categories->toArray());
        }
        return view('posts.categories', ['categories' => $categories]);
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
        if (empty($request->slug)){
            $baseSlug = SlugHelper::getSlug($request->name);
            $request->slug = $baseSlug;
            $slugCount = -1;
            while(Category::where('slug', '=', $request->slug)->exists()){
                $slugCount += 1;
                $request->slug = $baseSlug.'-'.$slugCount;
            }
        }

        $this->validate($request, [
            'name' => 'required',
            'slug' => 'unique:categories,slug'
        ]);
        $cat = new Category;
        $cat->name = $request->name;
        $cat->slug = $request->slug;
        $cat->template = $request->template;
        $cat->save();
        $request->session()->flash("message_success", "Category added.");
        return redirect()->action('Admin\CategoryController@index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cat = Category::where('id', $id)->first();
        return view('posts.category-single', ['category' => $cat]);
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
        //
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
        $cat = Category::find($id);
        $cat->delete();
        $request->session()->flash("message_success", "Category $id has been deleted.");
        return "Category $id has been deleted.";
    }
}
