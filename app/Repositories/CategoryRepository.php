<?php
/**
 * Created by PhpStorm.
 * User: minhdaobui
 * Date: 5/10/2015
 * Time: 4:26 PM
 */

namespace App\Repositories;

use App\BeaconMinor;
use App\Category;
use App\Facades\Connector;

class CategoryRepository implements CategoryRepositoryInterface
{

    public function getItemIDsFromCategories($categories)
    {
        $itemIDs = [];
        foreach ($categories as $category) {
            $itemIDs = array_merge($itemIDs, Connector::getItemIDsFromCategory($category));
        }

        return $itemIDs;
    }

    public function getTaxonomy($convertToArray = false)
    {
        return Connector::getTaxonomy($convertToArray);
    }

    public function getAllCategoryNodesOfItems($items)
    {
        $allCats = [];
        foreach ($items as $item) {
            $remoteCat = Connector::getCategoryFromItemID($item->id);
            if (!empty($remoteCat)) {
                $cat = Category::find($remoteCat->id);
                do {
                    if (in_array($cat->id, $allCats)) {
                        break;
                    }
                    $allCats[] = $cat->id;
                    $cat = $cat->parentCategory;
                } while ($cat != null);
            }
        }
        return $allCats;
    }
}