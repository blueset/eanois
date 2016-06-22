<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\SlugHelper;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
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
        $images = Image::paginate(20);
        return view('images.index', ['images' => $images]);
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

        $ext = $file->getClientOriginalExtension();
        $slug = SlugHelper::getNextAvailableSlug(basename($file->getClientOriginalName(), '.'.$ext), Image::class);
        $local_path = storage_path('app/images/'.strval(time()).'_'.$slug.'.'.$ext);
        $img_obj = \InterventionImage::make($file->getRealPath());
        $img_obj->save($local_path);

        $db_img = new Image([
            'slug' => $slug,
            'title' => $file->getClientOriginalName(),
            'path' => $local_path
        ]);
        $db_img->save();
    }

    /**
     * Display the specified resource.
     *
     * @param        $slug
     * @param        $ext
     * @param int    $w
     * @param int    $h
     * @param string $mode
     *
     * @return \Illuminate\Http\Response
     */
    public function show($slug, $ext=null, $w=0, $h=0, $mode="fit")
    {
        $db_img = Image::where('slug', '=', $slug)->firstOrFail();
        if($ext == null){
            $ext = \File::extension($db_img->path);
        }
        \InterventionImage::configure(["driver" => "imagick"]);
        $img = \InterventionImage::make($db_img->path);
        $w = intval($w);
        $h = intval($h);
        if ($w > 0 && $h > 0) {
            if ($mode == "fit") {
                $img->fit($w, $h);
            } elseif ($mode == "resize") {
                $img->resize($w, $h);
            }
        } elseif ($w > 0 && $h == 0) {
            $img->widen($w);
        } elseif ($w == 0 && $h > 0) {
            $img->heighten($h);
        }
        if($ext == "webp"){
            $icore = $img->getCore();
            $icore->setImageFormat('webp');
            $icore->setImageAlphaChannel(\Imagick::ALPHACHANNEL_ACTIVATE);
            $icore->setBackgroundColor(new \ImagickPixel('transparent'));
            return response($icore->getImagesBlob())
                   ->header('Content-Type', 'image/webp');
        }
        return $img->response($ext);
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
        $img = Image::find($id);
        $img->delete();
        $request->session()->flash("message_success", "Image $img->title has been deleted.");
        return "Image $img->title has been deleted.";
    }
}
