<?php
/**
 * Created by PhpStorm.
 * User: minhdaobui
 * Date: 5/7/2015
 * Time: 7:30 AM
 */

namespace App\Services;


use App\Category;
use App\ActiveCustomer;
use GuzzleHttp\Client;
use Carbon\Carbon;

class ConnectorService
{

    private $client;

    public function __construct()
    {
        $this->client = new Client(['base_url' => self::getSupermarketMSHost()]);
    }

    public static function getSupermarketMSHost()
    {
        if (config('supermarketms.use_localhost')) {
            return 'http://localhost:' . config('supermarketms.local_port');
        } else {
            return config('supermarketms.remote_host');
        }
    }


    public function getItemIDsFromCategory(Category $category)
    {
        return $this->getItemIDsFromCategoryID($category->id);
    }

    public function getItemIDsFromCategoryID($categoryID)
    {
        $response = $this->client->get('/categories/'.$categoryID.'/items');
        return json_decode($response->getBody());
    }

    public function getTaxonomy($convertToArray=false)
    {
        $response = $this->client->get('/taxonomy');
        return json_decode($response->getBody(),$convertToArray);
    }

    public function getShoppingHistoryFromCustomer(ActiveCustomer $customer, $fromDate = null, $toDate = null){

        if ($fromDate == null && $toDate == null)
        {
            $response = $this->client->get('/customers/'.$customer->id.'/shopping-history');
        }
        elseif ($fromDate != null && $toDate != null) {
            $response = $this->client->get('/customers/'.$customer->id.'/shopping-history'
                                            .'/'.$fromDate.'/'.$toDate);
        }
        else {
            $response = $this->client->get('/customers/'.$customer->id.'/shopping-history'
                                            .'/'.$fromDate);
        }

        return $response->json();
    }

}
