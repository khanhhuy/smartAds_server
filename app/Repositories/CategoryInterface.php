<?php
/**
 * Created by PhpStorm.
 * User: minhdaobui
 * Date: 5/10/2015
 * Time: 4:23 PM
 */
namespace App\Repositories;

use App\BeaconMinor;

interface CategoryInterface {
    public function getItemIDsFromCategories($categories);
}