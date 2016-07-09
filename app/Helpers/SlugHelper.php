<?php

namespace App\Helpers;


use Illuminate\Database\Eloquent\Model;

use GuzzleHttp\Client;

class SlugHelper {

    /**
     * Get Hiragana from Japanese Kana-Kanji-Majiri-Bun via labs.goo.ne.jp API.
     *
     * @param string $sentence The string to be converted
     *
     * @return string Hiragana text
     */
    public static function get_hiragana($sentence) {
        $url = "https://labs.goo.ne.jp/api/hiragana";
        $app_id = "24c330a457bb65c9221e928bfe0fd504022e4902c1e597352638bdd84c124adf";
        $output_type = "hiragana";
        $client = new Client();
        $response = $client->request('POST', $url, [
            'form_params' => [
                'app_id' => $app_id,
                'output_type' => $output_type,
                'sentence' => $sentence,
            ]
        ]);
        $rJson = json_decode((string) $response->getBody());
        $result = $rJson->converted;
        return $result;
    }

    /**
     * Convert string into slug format.
     * Non-latin chars are treated by the PHP transliterator.
     * `self::get_hiragana()` is gone through first when Japanese Kana presents.
     *
     * @param string $val String to be converted
     *
     * @return string slug
     */
    public static function getSlug($val) {
        if (preg_match('/[ぁ-んァ-ンｧ-ﾝﾞﾟ]+/u', $val)){
            $val = self::get_hiragana($val);
        }
        $result = transliterator_transliterate('Any-Latin; Latin-ASCII; NFD; [:Nonspacing Mark:] Remove; NFC;', $val);
        return \Slugify::slugify($result);
    }

    /**
     * Check with the database to find the next available slug
     * in the `slug` column of the table of the specified model.
     *
     * @param string    $slug  Slug to be processed
     * @param           $model Model to be checked
     * @param int|null  $id    ID of element to be exempted
     *
     * @return string Slug processed
     */
    public static function getNextAvailableSlug($slug, $model, $id=null) {
        $slug = self::getSlug($slug);
        $baseSlug = $slug;
        $slugCount = -1;

        $query = $model::withTrashed();
        if (!is_null($id)){
            $query = $query->where('id', '!=', $id);
        }
        while($query->where('slug', $slug)->exists()){
            $query = $model::withTrashed();
            if (!is_null($id)){
                $query = $query->where('id', '!=', $id);
            }
            $slugCount += 1;
            $slug = $baseSlug.'-'.$slugCount;
        }
        return $slug;
    }
}