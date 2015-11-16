<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ActiveCustomer;
use App\Facades\ProcessTransaction;

use Illuminate\Support\Facades\Queue;
use App\Commands\ProcessDelay;

use Carbon\Carbon;
use App\Item;
use App\Ads;

use Illuminate\Http\Request;

class AccountController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(ActiveCustomer $customer)
	{
		return $customer->watchingListWithBlackList()->get();
	}

	public function feedback(ActiveCustomer $customer, Request $request) {
		$adsId = $request->input('adsId');
		$ads = Ads::find($adsId);
		if ($ads == null)
			return;
		$items = $ads->items()->lists('id');
		$watchingList = $customer->watchingList()->lists('id');
		$blackList = $customer->blackList()->lists('id');

		foreach ($items as  $key => $item) {
			if (!in_array($item, $watchingList))
				unset($items[$key]);
			if(in_array($item, $blackList))
            	unset($items[$key]);
		}

        $customer->blackList()->attach($items);
       	$customer->watchingList()->detach($items);
		$customer->save();

		return $items;
	}

	public function update(ActiveCustomer $customer) {

		$timeRange = Setting::get('process-config.process_range_months');
		if ($timeRange == null)
			$timeRange = 6;
		$fromDate = Carbon::now()->subMonths($timeRange)->toDateString();

		if ($customer->last_mining == '0000-00-00 00:00:00') {
			Queue::push(new ProcessDelay($customer, $fromDate));
			return;
		}

		$lastProcessDate = Carbon::createFromFormat('Y-m-d H:i:s', $customer->last_mining);
		if (Carbon::now()->subDays(config('process-trans.process_range_lastProcessDays'))->gt($lastProcessDate)) {
			//testing - 10 secs
			$time = Carbon::now()->addSecond(10);
			//Queue::push($time, new ProcessDelay($customer, $lastProcessDate->toDateString()));
			Queue::later($time, new ProcessDelay($customer, $lastProcessDate->toDateString()));
		}

		return;
	}
}
