<?php

namespace App\Facades;


use Illuminate\Support\Facades\Facade;

class ProcessTransaction extends Facade {

    protected static function getFacadeAccessor() { return 'processTransaction'; }

}