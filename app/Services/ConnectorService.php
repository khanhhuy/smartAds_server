<?php
/**
 * Created by PhpStorm.
 * User: minhdaobui
 * Date: 5/7/2015
 * Time: 7:30 AM
 */

namespace App\Services;



use App\Category;
use GuzzleHttp\Client;

class ConnectorService {

    const SUPERMARKET_HOST='http://localhost:8088';


    public function getItemIDsFromCategory(Category $category)
    {
        return $this->getItemIDsFromCategoryID($category->id);
    }

    public function getItemIDsFromCategoryID($categoryID)
    {
        $client=new Client(['base_url'=>self::SUPERMARKET_HOST]);
        $response=$client->get('/categories/'.$categoryID.'/items');
        return $response->json();
    }
}
