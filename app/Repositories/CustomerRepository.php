<?php
/**
 * Created by PhpStorm.
 * User: minhdaobui
 * Date: 9/30/2015
 * Time: 12:37 PM
 */

namespace App\Repositories;


use App\Facades\Connector;

class CustomerRepository implements CustomerRepositoryInterface
{

    public function getCustomerIDFromEmail($email)
    {
        $customer=Connector::getCustomerFromEmail($email);
        if ($customer !== null) {
            return $customer->id;
        }
        else{
            return null;
        }
    }

    public function getCustomerFromEmail($email)
    {
        $customer=Connector::getCustomerFromEmail($email);
        return $customer;
    }

    public function getCustomerInfo($id)
    {
        $info = Connector::getCustomerInfo($id);
        return $info;
    }

    public function getAllJobDesc() {
        $jobsDesc = Connector::getJobDesc();
        return $jobsDesc;
    }

    public function getShoppingHistory($customerID, $from = null, $to = null)
    {
        return Connector::getShoppingHistoryFromCustomer($customerID, $from, $to);
    }
}