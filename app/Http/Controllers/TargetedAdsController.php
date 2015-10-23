<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\AdsController;
use Utils;
use App\Facades\Connector;
use App\Repositories\CustomerRepositoryInterface;
use App\TargetedRule;
use App\ActiveCustomer;
use App\Ads;
use Carbon\Carbon;

use Illuminate\Http\Request;

class TargetedAdsController extends AdsController {

	private $customerRepo;

    public function __construct(CustomerRepositoryInterface $customerRepo)
    {
        $this->customerRepo = $customerRepo;
    }

    public function manageTargeted(Request $request)
    {
        return view('ads.targeted.manage');
    }

    public function createTargeted()
    {
        $ads = new Ads;
        $items = [];
        $jobs = $this->customerRepo->getAllJobDesc();
        return view('ads.targeted.create')->with(compact(['ads', 'items', 'jobs']));
    }

	public function getRule() {
		//return $this->customerRepo->getCustomerInfo('1');
		//return Ads::find('1')->targetedRule()->get();
		return TargetedRule::all();
	}

	public function targetedTable(Request $request)
    {
        $allTargeted = Ads::targeted();
        $r['draw'] = (int)$request->input('draw');
        $r['recordsTotal'] = $allTargeted->count();
        $r['recordsFiltered'] = $r['recordsTotal'];
        $displayPromotions = $allTargeted->skip($request->input('start'))->take($request->input('length'))->orderBy('updated_at', 'asc')->get();
        $r['data'] = $displayPromotions->map(function ($ads) {
            return [
                $ads->id,
                $ads->title,
                Utils::formatTargets($ads->targets),
                'TODO Huy',
                Carbon::parse($ads->getOriginal('start_date'))->format('m-d-Y'),
                Carbon::parse($ads->getOriginal('end_date'))->format('m-d-Y'),
                $ads->updated_at->format('m-d-Y'),
            ];
        });
        return response()->json($r);
    }
}