<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    public function category() {
        return $this->belongsTo('App\Category', 'category');
    }

    public function tags() {
        return $this->belongsToMany('App\Tag', 'posts_tags', 'post', 'tag');
    }

    public function links() {
        return $this->hasMany('App\PostLink', 'post', 'post_links');
    }

    public function meta() {
        return $this->hasMany('App\PostMeta', 'post', 'post_meta');
    }
    
    protected $dates = ['deleted_at'];
}
