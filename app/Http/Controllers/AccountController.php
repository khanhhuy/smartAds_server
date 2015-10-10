<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ActiveCustomer;
use App\Facades\ProcessTransaction;

use Illuminate\Support\Facades\Queue;
use App\Commands\ProcessDelay;

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

		$from = Carbon::now()->subMonths(config('process-trans.process_range_months'))->toDateString();
		$lastProcessDate = Carbon::createFromFormat('Y-m-d H:i:s', $customer->last_mining);

		if (Carbon::now()->subDays(config('process-trans.process_range_lastProcessDays'))
					->gt($lastProcessDate)) {
			ProcessTransaction::processCustomer($customer, false, $from);
		}
		//update after 18h
		else {
			//testing - 10 secs
			$time = Carbon::now()->addSecond(10);
			Queue::later($time, new ProcessDelay($customer, $lastProcessDate->toDateString()));
		}

		$customer->last_mining = Carbon::now();
		return $customer->watchingList()->get();
	}

}
