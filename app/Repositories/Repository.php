<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class Repository
{
    /**
     * Model instance
     *
     * @var Model|Builder $model
     */
    protected $model;

    /**
     * Create a new Repositories instance
     * Repositories constructor.
     *
     * @param $model
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * Update or create the model by the given keys
     *
     * @param array $keys
     * @param array $values
     *
     * @return Model
     */
    public function updateOrCreate(array $keys, array $values): Model
    {
        return $this->model->updateOrCreate($keys, $values);
    }
}
