<?php
/**
 * Created by PhpStorm.
 * User: minhdaobui
 * Date: 9/26/2015
 * Time: 9:24 AM
 */

namespace App\Repositories;


use App\Facades\Connector;

class StoreRepository implements StoreRepositoryInterface
{

    public function getAllStores($convertToArray=false)
    {
        return Connector::getStores($convertToArray);
    }
}