<?php
namespace App\Utils;
use Illuminate\Support\Facades\Request;

/**
 * Created by PhpStorm.
 * User: minhdaobui
 * Date: 10/2/2015
 * Time: 9:41 PM
 */
class Utils
{
    public static function emptyObject($obj)
    {
        return empty((array)$obj);
    }

    public static function setActiveClassManager($condition)
    {
        $condition='manager/'.$condition;
        return Request::is($condition)? 'class="active"':'';
    }
}