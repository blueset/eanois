<?php

namespace App;

use App\Scopes\PublishDateScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new PublishDateScope);
    }

    public function category() {
        return $this->belongsTo('App\Category', 'category');
    }

    public function tags() {
        return $this->belongsToMany('App\Tag', 'posts_tags', 'post', 'tag');
    }

    public function links() {
        return $this->hasMany('App\PostLink', 'post');
    }

    public function meta() {
        return $this->hasMany('App\PostMeta', 'post');
    }
    
    protected $dates = ['deleted_at'];
}
