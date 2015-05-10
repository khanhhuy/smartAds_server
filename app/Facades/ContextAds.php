<?php
/**
 * Created by PhpStorm.
 * User: minhdaobui
 * Date: 5/10/2015
 * Time: 1:58 PM
 */

namespace App\Facades;


use Illuminate\Support\Facades\Facade;

class ContextAds extends Facade {
    protected static function getFacadeAccessor() { return 'contextAdsService'; }
}