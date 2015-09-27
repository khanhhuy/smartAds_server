<?php
/**
 * Created by PhpStorm.
 * User: minhdaobui
 * Date: 5/10/2015
 * Time: 4:23 PM
 */
namespace App\Repositories;

use App\BeaconMinor;

interface CategoryRepositoryInterface {
    public function getItemIDsFromCategories($categories);
    public function getTaxonomy($convertToArray);
    public function getAllCategoryNodesOfItems($items);
}