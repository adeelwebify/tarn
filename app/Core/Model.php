<?php

namespace Tarn\Core;

use Illuminate\Database\Eloquent\Model as EloquentModel;

abstract class Model extends EloquentModel {
    /**
     * The connection name for the model.
     * Overriding this just to make sure developers know they can customize it.
     *
     * @var string|null
     */
    // protected $connection = 'default';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    // protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
}
