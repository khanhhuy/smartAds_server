<?php namespace App\Http\Controllers;

use App\ActiveCustomer;
use App\Ads;
use App\Commands\ProcessDelay;
use App\Http\Requests;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Queue;

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

		$fromDate = Carbon::now()->subMonths(config('process-trans.process_range_months'))->toDateString();

		if ($customer->last_mining == '0000-00-00 00:00:00') {
//			ProcessTransaction::processCustomer($customer, $fromDate);
//			$customer->last_mining = Carbon::now();
//			$customer->save();
			Queue::push(new ProcessDelay($customer, $fromDate));
            return 'FIRST_TIME';
		}

		$lastProcessDate = Carbon::createFromFormat('Y-m-d H:i:s', $customer->last_mining);
		if (Carbon::now()->subDays(config('process-trans.process_range_lastProcessDays'))->gt($lastProcessDate)) {
			//testing - 10 secs
			$time = Carbon::now()->addSecond(10);
			Queue::later($time, new ProcessDelay($customer, $lastProcessDate->toDateString()));
//            ProcessTransaction::processCustomer($customer, $lastProcessDate->toDateString());
//            $customer->last_mining = Carbon::now();
//            $customer->save();
            return 'QUEUED';
		} else {
            return 'ALREADY_QUEUED';
        }
	}
}
