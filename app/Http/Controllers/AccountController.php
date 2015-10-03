<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ActiveCustomer;
use App\Facades\Mining;

use Illuminate\Support\Facades\Queue;
use App\Commands\MiningDelay;

use Carbon\Carbon;

use Illuminate\Http\Request;

class AccountController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	public function update(ActiveCustomer $customer) {

		$from = Carbon::now()->subMonths(config('mining.mining_range_months'))->toDateString();
		$lastMiningDate = Carbon::createFromFormat('Y-m-d H:i:s', $customer->last_mining);

		if (Carbon::now()->subDays(config('mining.mining_range_lastMiningDays'))
					->gt($lastMiningDate)) {
			Mining::miningCustomer($customer, false, $from);
		}
		//update after 18h
		else {
			$time = Carbon::now()->addMinutes(10);
			Queue::later($time, new MiningDelay($customer, $lastMiningDate->toDateString()));
			//Queue::push(new MiningDelay($customer, $lastMiningDate));
		}

		$customer->last_mining = Carbon::now();
		return $customer->watchingList()->get();
	}

}
