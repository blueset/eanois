<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "config";
    /**
     * Primary key of the model.
     *
     * @var string
     */
    public $primaryKey = "key";
    /**
     * Indicating expectation of timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    public function index() {
        $config = self::all();

        return view('config.index', ['config' => $config]);
    }

    public static function getConfig($value) {
        $item = self::where('key', $value)->first();
        return $item['value'];
    }
}
