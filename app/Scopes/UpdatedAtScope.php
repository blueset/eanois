<?php

namespace App\Scopes;

use DebugBar\DebugBar;
use Doctrine\Common\Util\Debug;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class UpdatedAtScope implements Scope {
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Builder $builder, Model $model)
    {
        return $builder->orderBy("created_at", "desc");
    }
}