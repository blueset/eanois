<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\SlugHelper;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Image;
use Illuminate\Support\Facades\Storage;


class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $images = Image::paginate(20);
        if ($request->wantsJson()){
            return $images;
        }
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
        $local_path = 'images/';
        $local_name = strval(time()).'_'.$slug.'.'.$ext;
        Storage::put($local_path . $local_name, file_get_contents($file->getRealPath()));

        $db_img = new Image([
            'slug' => $slug,
            'title' => $file->getClientOriginalName(),
            'path' => $local_path . $local_name,
        ]);
        $db_img->save();

        return response()->json([
            "id" => $db_img->id,
            "slug" => $db_img->slug,
            "title" => $db_img->title
        ]);
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
        $db_img = Image::where('slug', '=', $slug)->select(["path"])->firstOrFail();
        if (($ext == null || $ext == $db_img->getExt()) && $w == 0 && $h == 0){
            try {
                return response()->file(Storage::get($db_img->path));
            } catch (\Exception $e) {
                return response()->file(storage_path("app/".$db_img->path));
            }
        }
        if ($ext == null){
            $ext = \File::extension($db_img->path);
        }
        \InterventionImage::configure(["driver" => "imagick"]);
        $callback = function($image) use ($db_img, $w, $h, $mode, $ext){
            $image->make(Storage::get($db_img->path));
            $w = intval($w);
            $h = intval($h);
            if ($w > 0 && $h > 0) {
                if ($mode == "fit") {
                    $image->fit($w, $h);
                } elseif ($mode == "resize") {
                    $image->resize($w, $h);
                }
            } elseif ($w > 0 && $h == 0) {
                $image->widen($w);
            } elseif ($w == 0 && $h > 0) {
                $image->heighten($h);
            }
            if($ext == "webp"){
                $image->response("png");
//            $icore = $image->getCore();
//            $icore->setImageFormat('webp');
//            $icore->setImageAlphaChannel(\Imagick::ALPHACHANNEL_ACTIVATE);
//            $icore->setBackgroundColor(new \ImagickPixel('transparent'));
//            return response($icore->getImagesBlob())
//                   ->header('Content-Type', 'image/webp');
            }
        };
        $img = \InterventionImage::cache($callback, 5, true);
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
    public function destroy(Request $request, $id)
    {
        $img = Image::find($id);
        $img->delete();
        $request->session()->flash("message_success", "Image $img->title has been deleted.");
        return response("Image $img->title has been deleted.");
    }
}
