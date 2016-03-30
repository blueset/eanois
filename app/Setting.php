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

    public function getKeyAttribute($value) {
        return $value;
    }

    /**
     * Get Config.
     *
     * @param string $value Key of config item. Empty for all items.
     *
     * @return string|array String: Value. Array: `$key => $value`.
     */
    public static function getConfig($value = "") {
        if ($value == ""){
            $result = [];
            $query = self::all();
            foreach ($query as $i) {
                $result[$i['key']] = $i['value'];
            }
            return $result;
        }
        $item = self::where('key', $value)->first();
        return $item['value'];
    }

    public static function setConfig($configs, $new = false) {
        foreach ($configs as $key => $value) {
            if ($new) {
                $rec = self::firstOrNew(['key'=>$key]);
            } else {
                $rec = self::where('key', $key)->first();
                if ($rec == null){
                    continue;
                }
            }
            $rec->value = $value;
            $rec->save();
        }
    }
}
