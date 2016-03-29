<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;

class EanoisViewComposer
{

    /**
     * Create a new profile composer.
     *
     * @internal param UserRepository $users
     */
    public function __construct() {
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view) {
        if (config('eanois.stage') == 'backend' && \Auth::check()){
            /*
             * Backend Composing
             */

            // User
            $view->with('user', \Auth::user());
        }
        $view->with('count', 1);
        $view->with('view_name', $view->getName());
    }
}