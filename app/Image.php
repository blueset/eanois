<?php

namespace App;

use App\Scopes\UpdatedAtScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = ['slug', 'title', 'path'];
    protected $hidden = ['path'];
    protected $appends = ['backend_media_preview_html'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new UpdatedAtScope);
    }

    public function getExt(){
        return strtolower(\File::extension($this->path));
    }

    public function pictureElement(){
        return new ImagePictureElement($this);
    }

    public function getBackendMediaPreviewHtmlAttribute() {
        return utf8_encode($this->pictureElement()->mode("WidthHeight")->width(200)->height(135));
    }
}

class ImagePictureElement {

    public function __construct(Image $image) {
        $this->image = $image;
        $this->noWebp = false;
        $this->mode = "";
        $this->width = 0;
        $this->height = 0;
        $this->title = $image->title;
    }

    public function noWebp($mode) {
        $this->noWebp = $mode;
        return $this;
    }
    public function mode($mode) {
        $this->mode = $mode;
        return $this;
    }
    public function title($title) {
        $this->title = $title;
        return $this;
    }
    public function width($width) {
        $this->width = $width;
        return $this;
    }
    public function height($height) {
        $this->height = $height;
        return $this;
    }
    private function is_animated() {
        // Check if a GIF is animated by ZeBadger
        // https://secure.php.net/manual/en/function.imagecreatefromgif.php#59787
        if ($this->image->getExt() != "gif"){
            return false;
        }
        if(!($fh = @fopen($this->image->path, 'rb')))
            return false;
        fclose($fh);

        $filecontents = file_get_contents($this->image->path);

        $str_loc=0;
        $count=0;
        while ($count < 2){

            $where1 = strpos($filecontents,"\x00\x21\xF9\x04",$str_loc);
            if ($where1 === FALSE){
                break;
            } else {
                $str_loc=$where1+1;
                $where2=strpos($filecontents,"\x00\x2C",$str_loc);
                if ($where2 === FALSE){
                    break;
                } else {
                    if ($where1 + 8 == $where2) {
                        $count++;
                    }
                    $str_loc = $where2 + 1;
                }
            }
        }

        return $count > 1;
    }

    public function render(){
        $para = [$this->image->slug];
        if ($this->mode == "Height") {
            array_push($para, $this->height);
        } elseif ($this->mode == "Width") {
            array_push($para, $this->width);
        } elseif ($this->mode == "WidthHeight") {
            array_push($para, $this->width, $this->height);
        }
        $imgtag = '<img src="' . route("AdminImageControllerShow".$this->mode, $para) . '" alt="' . $this->title . '">';
        $webptag = "";
//        if ($this->mode == "" && $this->is_animated()){
//            $webptag = "";
//        } else {
//            array_push($para, "webp");
//            $webptag = "";
//            $webptag = '<source srcset="' . route("AdminImageControllerShow".$this->mode."Ext", $para) . '" type="image/webp">';
//        }
        $frame = "<picture>$webptag$imgtag</picture>";
        return $frame;
    }

    public function __toString() {
        return $this->render();
    }
}
