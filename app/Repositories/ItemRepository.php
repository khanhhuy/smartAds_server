<?php
/**
 * Created by PhpStorm.
 * User: minhdaobui
 * Date: 9/26/2015
 * Time: 9:24 AM
 */

namespace App\Repositories;


use Connector;

class ItemRepository implements ItemRepositoryInterface
{

    public function getItemNameByID($id)
    {
        return Connector::getItemNameByID($id);
    }

    public function getItemNamesByIDs($ids)
    {
        if (empty($ids)) {
            return [];
        } else {
            return Connector::getItemNamesByIDs($ids);
        }
    }

    public function searchItemsGetIDs($query)
    {
        return Connector::searchItemsGetIDs($query);
    }
}