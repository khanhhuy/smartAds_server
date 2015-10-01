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
        $this->client = new Client(['base_uri' => self::getSupermarketMSHost()]);
    }

    public static function getSupermarketMSHost()
    {
        if (config('supermarketms.use_localhost')) {
            return 'http://localhost:' . config('supermarketms.local_port');
        } else {
            switch (config('supermarketms.remote_host')){
                case 'b':
                    return config('supermarketms.msms_byethost');
                case 'h':
                    return config('supermarketms.msms_hostinger');
            }
            return config('supermarketms.msms_hostinger');
        }
    }


    public function getItemIDsFromCategory(Category $category)
    {
        return $this->getItemIDsFromCategoryID($category->id);
    }

    public function getItemIDsFromCategoryID($categoryID)
    {
        $response = $this->client->get('categories/'.$categoryID.'/items');
        return json_decode($response->getBody());
    }

    public function getTaxonomy($convertToArray=false)
    {
        $response = $this->client->get('taxonomy');
        return json_decode($response->getBody(),$convertToArray);
    }

    public function getShoppingHistoryFromCustomer(ActiveCustomer $customer, $fromDate = null, $toDate = null){

        if ($fromDate == null && $toDate == null)
        {
           $response = $this->client->get('customers/'.$customer->id.'/shopping-history');
        }
        elseif ($fromDate != null && $toDate != null) {
            $response = $this->client->get('customers/'.$customer->id.'/shopping-history'
                                            .'?from='.$fromDate.'&to='.$toDate);
        }
        else {
            $response = $this->client->get('customers/'.$customer->id.'/shopping-history'
                                            .'?from='.$fromDate);
        }

        return json_decode($response->getBody());
    }

    public function getStores($convertToArray=false)
    {
        $response=$this->client->get('stores');
        return json_decode($response->getBody(),$convertToArray);
    }

    public function getCategoryIDFromItemID($itemID)
    {
        $r=$this->client->get('items/'.$itemID.'/category');
        $cat=json_decode($r->getBody());
        return $cat->id;
    }

    public function validateAuthentication($email,$password)
    {
        $response=$this->client->post('auth/validate',[
            'form_params'=>compact('email','password')
        ]);
        return json_decode($response->getBody());
    }

    public function getCustomerFromEmail($email)
    {
        $response=$this->client->get('customer-by-email',[
           'query'=>compact('email')
        ]);
        return json_decode($response->getBody());
    }
}
