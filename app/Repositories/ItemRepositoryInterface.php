<?php
/**
 * Created by PhpStorm.
 * User: minhdaobui
 * Date: 5/10/2015
 * Time: 4:23 PM
 */
namespace App\Repositories;

interface ItemRepositoryInterface {
    public function getItemNameByID($id);
}