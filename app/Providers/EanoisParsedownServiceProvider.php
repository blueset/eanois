<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class EanoisParsedown extends \Parsedown {

    protected function inlineImage($Excerpt) {
        $Image = parent::inlineImage($Excerpt);
        if (substr($Image['element']['attributes']['src'], 0, 6) == "image:") {
            $slug  = substr($Image['element']['attributes']['src'], 6);
            $title = $Image['element']['attributes']['alt'];
            $text  = \App\Image::where('slug', $slug)->first();
            if (!$text->exists()){return;}
            $text  = $text->pictureElement();
            if (!empty($title)) {
                $text = $text->title($title);
            }

            $Image['element']['name']       = 'div';
            $Image['element']['text']       = $text->render();
            $Image['element']['attributes'] = [];
            return $Image;
        } else {
            return $Image;
        }
    }
}

class EanoisParsedownServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot() {
    }
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {
        $this->app->singleton(EanoisParsedown::class, function($app){
            return EanoisParsedown::instance();
        });
    }
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        return [EanoisParsedown::class];
    }

}
