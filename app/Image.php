<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = ['slug', 'title', 'path'];

    public function getExt(){
        return \File::extension($this->path);
    }
}
