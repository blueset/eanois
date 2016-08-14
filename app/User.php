<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function meta() {
        return $this->hasMany('App\UserMeta', 'user');
    }

    public function getMeta() {
        $meta = $this->meta()->get();
        $dict = [
            "display_name" => $this->name,
            "url" => url('/')
        ];
        foreach ($meta as $i) {
            $dict[$i->key] = $i->value;
        }
        return $dict;
    }

    public function setMeta($data) {
        $allowed = ["display_name", "url"];
        foreach($data as $k => $v) {
            if (in_array($k, $allowed)) {
                $val = $this->meta()->firstOrNew(["key" => $k]);
                $val->value = $v;
                $val->user = $this->id;
                $val->save();
            }
        }
    }
}
