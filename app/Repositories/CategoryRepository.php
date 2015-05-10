<?php
/**
 * Created by PhpStorm.
 * User: minhdaobui
 * Date: 5/10/2015
 * Time: 4:26 PM
 */

namespace App\Repositories;

use App\BeaconMinor;
use App\Facades\Connector;

class CategoryRepository implements CategoryInterface{

    public function getItemIDsFromCategories($categories)
    {
        $itemIDs = [];
        foreach ($categories as $category) {
            $itemIDs = array_merge($itemIDs, Connector::getItemIDsFromCategory($category));
        }

        return $itemIDs;
    }
}