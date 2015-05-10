<?php
/**
 * Created by PhpStorm.
 * User: minhdaobui
 * Date: 5/7/2015
 * Time: 12:02 PM
 */

namespace App\Facades;


use Illuminate\Support\Facades\Facade;

class Connector extends Facade {

    protected static function getFacadeAccessor() { return 'connector'; }

}