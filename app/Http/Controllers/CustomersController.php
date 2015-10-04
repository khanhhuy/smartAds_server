<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\CustomerRepositoryInterface;
use Illuminate\Http\Request;

class CustomersController extends Controller {

    private $customerRepo;
    public function __construct(CustomerRepositoryInterface $customerRepo)
    {
        $this->customerRepo=$customerRepo;
    }
    public function accountStatus(Request $request)
    {
        if (!$request->has('email')) {
            return $this->badRequest();
        }
        $customer = $this->customerRepo->getCustomerFromEmail($request->input('email'));
        if (empty($customer)){
            $r['result']='NOT_A_MEMBER';
        }
        else{
            if (!$customer->havePassword){
                $r['result']='DONT_HAVE_PASSWORD';
            }
            else{
                $r['result']='HAVE_PASSWORD';
            }
        }
        return $r;
    }

}
