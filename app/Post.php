<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function category() {
        $this->belongsTo('App\Category', 'category');
    }

    public function tags() {
        $this->belongsToMany('App\Tag', 'posts_tags', 'post', 'tag');
    }

    public function links() {
        $this->hasMany('App\PostLink', 'post_links', 'post');
    }

    public function meta() {
        $this->hasMany('App\PostMeta', 'post_meta', 'post');
    }
}
