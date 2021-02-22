<?php

namespace KartMax;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use KartMax\Database;

abstract class Model extends EloquentModel
{
    // public function __construct()
    // {
    //     $capsuleInstance = new Database;
    //     $capsuleInstance->capsule->bootEloquent();
    // }
}
