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

    public static function formatItem($name, $id)
    {
        return  $name . " [" . $id . "]";
    }

    public static function formatTargets($targets)
    {
        if (empty($targets)){
            return ['All'];
        }
        else {
            return $targets;
        }

    }
}