<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\SlugHelper;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\UploadedFile;
use App\Image;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('images.index');
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
        // type pre-process
        $file = $request->file('file');
//        dump($request);
        $this->validate($request, [
            'file' => 'required|mimetypes:image/bmp,image/gif,image/jpeg,image/png,image/tiff,image/svg+xml,image/webp'
        ]);

        $slug = SlugHelper::getNextAvailableSlug($file->getClientOriginalName(), Image::class);
        $ext = $file->getClientOriginalExtension();
        $local_path = storage_path('app/images/'.strval(time()).'_'.$slug.$ext);
        $img_obj = InterventionImage::make($file->getRealPath());
        $img_obj->save($local_path);

        $db_img = new Image([
            'slug' => $slug,
            'title' => $images->getClientOriginalName(),
            'path' => $local_path
        ]);
        $db_img->save();
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
