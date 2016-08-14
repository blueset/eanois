<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);
        \Validator::extend('password_hash', function($attribute, $value, $parameters, $validator){
            list($table, $column, $id) = $parameters;
            $pw = \DB::table($table)->select(["id", $column])->first(["id" => $id])->$column;
            $verify = \Hash::check($value, $pw);
            return $verify;
        });
        //
    }
}
