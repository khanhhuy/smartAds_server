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
use App\Utils\Utils;

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

    private static function decodeResponse($response,$toArray=false){
        $result=json_decode($response->getBody(),$toArray);
        if (Utils::emptyObject($result)){
            return null;
        }
        else{
            return $result;
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

        return json_decode($response->getBody(), true);
    }

    public function getCustomerInfo($id) {
        $response = $this->client->get('customer/'.$id.'/personal-info');
        return json_decode($response->getBody(), true);
    }

    public function getStores($convertToArray=false)
    {
        $response=$this->client->get('stores');
        return json_decode($response->getBody(),$convertToArray);
    }

    public function getCategoryFromItemID($itemID)
    {
        $r=$this->client->get('items/'.$itemID.'/category');
        return self::decodeResponse($r);
    }

    public function validateAuthentication($email,$password)
    {
        $response=$this->client->post('auth/validate',[
            'form_params'=>compact('email','password')
        ]);
        $r=json_decode($response->getBody());
        return $r->result;
    }

    public function getCustomerFromEmail($email)
    {
        $response=$this->client->get('customer-by-email',[
           'query'=>compact('email')
        ]);
        return self::decodeResponse($response);
    }

    public function registerCustomer($email, $password)
    {
        define('MAX', '10');
        for ($i=0;$i<MAX;$i++){
            $response=$this->client->post('auth/register',[
                'form_params'=>compact('email','password')
            ]);
            $result=json_decode($response->getBody());
            if ($result->result==true){
                return true;
            }
        }
        return false;
    }

    public static function getItemSearchURL()
    {
        return self::getSupermarketMSHost().'/search';
    }

    public function getItemNameByID($itemID)
    {
        $r= $this->client->get('items/'.$itemID.'/name');
        $name=(string)$r->getBody();
        return $name;
    }


    public function getItemNamesByIDs($item_ids)
    {
        $r= $this->client->get('items/names',[
            'query'=>compact('item_ids')
        ]);
        return self::decodeResponse($r,true);
    }

}
