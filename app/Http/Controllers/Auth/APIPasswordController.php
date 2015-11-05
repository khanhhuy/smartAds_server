<?php namespace App\Http\Controllers\Auth;

use App\ActiveCustomer;
use App\Http\Controllers\Controller;
use Connector;
use Illuminate\Http\Request;

class APIPasswordController extends Controller
{

    public function __construct()
	{
    }

    public function update(ActiveCustomer $customer, Request $request)
    {
        if (!$request->has('current_pass') || !$request->has('new_pass')) {
            return $this->badRequest();
        }
        return Connector::changePassword($customer->id, $request->only(['current_pass', 'new_pass']));
    }

}
