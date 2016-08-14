<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserMeta extends Model
{
    protected $table = 'user_meta';
    protected $dates = ['deleted_at'];
    public $timestamps = false;

    protected $fillable = [
        'key', 'value', 'user'
    ];
}
