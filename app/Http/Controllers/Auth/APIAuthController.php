<?php namespace App\Http\Controllers\Auth;

use App\ActiveCustomer;
use App\Facades\Connector;
use App\Http\Controllers\Controller;
use App\Repositories\CustomerRepositoryInterface;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use stdClass;

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
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
        if (!$request->has('email') || !$request->has('password')) {
            return $this->badRequest();
        }

        $validateResult = Connector::validateAuthentication($request->input('email'), $request->input('password'));
        if ($validateResult) {
            $customerID = $this->customerRepo->getCustomerIDFromEmail($request->input('email'));
            $customer = ActiveCustomer::firstOrCreate(['id' => $customerID]);
            $token = $this->refreshRememberToken($customer);
            $r['customerID'] = $customerID;
            $r['accessToken'] = $token;
            return response()->json($r);
        } else {
            return $this->respondWithErrorMessage('Wrong email or password');
        }
    }

    public function postRegister(Request $request)
    {
        if (!$request->has('email') || !$request->has('password')) {
            return $this->badRequest();
        }
        $customer = $this->customerRepo->getCustomerFromEmail($request->input('email'));
        if (!empty($customer)&&!$customer->havePassword){
            $r['result']=Connector::registerCustomer($request->input('email'), $request->input('password'));
        }
        else{
            $r['result']=false;
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
