<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostMeta extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'post_meta';
    public $timestamps = false;
}
