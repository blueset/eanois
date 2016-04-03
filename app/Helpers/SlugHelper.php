<?php

namespace App\Helpers;

use GuzzleHttp\Client;

class SlugHelper {

    public static function get_romaji($sentence) {
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

    public static function getSlug($val) {
        if (preg_match('/[ぁ-んァ-ンｧ-ﾝﾞﾟ]+/u', $val)){
            $val = self::get_romaji($val);
        }
        $result = transliterator_transliterate('Any-Latin; Latin-ASCII; NFD; [:Nonspacing Mark:] Remove; NFC;', $val);
        return \Slugify::slugify($result);
    }
}