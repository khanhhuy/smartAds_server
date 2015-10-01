<?php namespace App\Http\Controllers\Auth;

use App\ActiveCustomer;
use App\Facades\Connector;
use App\Http\Controllers\Controller;
use App\Repositories\CustomerRepositoryInterface;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;

class APIAuthController extends Controller
{

    protected $customerRepo;

    /**
     * Create a new authentication controller instance.
     *
     * @param UserProvider $provider
     * @param CustomerRepositoryInterface $customerRepo
     * @internal param Guard $auth
     * @internal param Registrar $registrar
     */
    public function __construct(CustomerRepositoryInterface $customerRepo)
    {
        $this->customerRepo = $customerRepo;

        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email', 'password' => 'required',
        ]);

        $validateResult = Connector::validateAuthentication($request->input('email'),$request->input('password'));
        if ($validateResult) {
            $customerID = $this->customerRepo->getCustomerIDFromEmail($request->input('email'));
            $customer = ActiveCustomer::firstOrCreate(['id'=>$customerID]);
            $token = $this->refreshRememberToken($customer);
            $r['customerID'] = $customerID;
            $r['token'] = $token;
        }
        else{
            $r=false;
        }
        return response()->json($r);
    }

    private function refreshRememberToken(ActiveCustomer $customer)
    {
        $customer->setRememberToken($token = str_random(60));
        $customer->save();
        return $token;
    }
}
