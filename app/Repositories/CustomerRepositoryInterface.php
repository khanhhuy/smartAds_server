<?php
/**
 * Created by PhpStorm.
 * User: minhdaobui
 * Date: 9/30/2015
 * Time: 12:36 PM
 */

namespace App\Repositories;


interface CustomerRepositoryInterface
{
    public function getCustomerIDFromEmail($email);
}