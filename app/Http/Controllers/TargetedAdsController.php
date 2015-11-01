<?php namespace App\Http\Controllers;

use App\Ads;
use App\Http\Requests;
use App\Http\Requests\TargetedRequest;
use App\Repositories\CustomerRepositoryInterface;
use App\TargetedRule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Utils;


class TargetedAdsController extends AdsController
{

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

    public function targetedTable(Request $request)
    {
        $TARGETED_ADS_COLUMNS = ['id', 'title', 'areas', 'targeted_customers', 'start_date', 'end_date', 'updated_at'];
        $allTargeted = Ads::targeted();
        $r['draw'] = (int)$request->input('draw');
        $r['recordsTotal'] = $allTargeted->count();
        $filtered = $allTargeted;

        //search
        $noResult = false;
        $cols = $request->input("columns");
        for ($c = 1; $c < 7; $c++) {
            $val = $cols[$c]['search']['value'];
            if (!empty($val) && !$noResult) {
                $colName = $TARGETED_ADS_COLUMNS[$c - 1];
                switch ($colName) {
                    case 'id':
                        $val = trim($val);
                        $filtered = $filtered->whereRaw("id LIKE ?", ["$val%"]);
                        break;
                    case 'title':
                        $val = trim($val);
                        $filtered = $filtered->whereRaw("title LIKE ?", ["%$val%"]);
                        break;
                    case 'areas':
                        $noResult = Utils::filterByAreas($filtered, $val);
                        break;
                    case 'targeted_customers':
                        //TODO Huy filter search text
                        break;
                    case 'start_date':
                    case 'end_date':
                        Utils::filterByFromToBased($filtered, $val, $colName);
                        break;
                    default:
                        break;
                }
            }
        }

        if (!$noResult) {
            $r['recordsFiltered'] = $filtered->count();

            //order
            if ($request->has('order')) {
                $order = $request->input('order');
                $orderColumn = $TARGETED_ADS_COLUMNS[$order[0]['column'] - 1];
                switch ($orderColumn) {
                    case 'areas':
                        $displayAds = Utils::sortByAreasThenSlice($filtered, $order[0]['dir'],
                            $request->input('start'), $request->input('length'));
                        break;
                    case 'targeted_customers':
                        //TODO Huy: sort by targeted customers
                        break;
                    default:
                        $displayAds = $filtered->skip($request->input('start'))->take($request->input('length'))
                            ->orderBy($orderColumn, $order[0]['dir'])->get();
                        break;
                }
            } else {
                $displayAds = $filtered->skip($request->input('start'))->take($request->input('length'))->orderBy('updated_at', 'asc')->get();
            }

            //transform
            $r['data'] = $displayAds->map(function ($ads) {
                return [
                    $ads->id,
                    $ads->title,
                    Utils::formatTargets($ads->targets),
                    Utils::formatRules($ads->targetedRule()->get()),
                    Carbon::parse($ads->getOriginal('start_date'))->format('m-d-Y'),
                    Carbon::parse($ads->getOriginal('end_date'))->format('m-d-Y'),
                ];
            });
        } else {
            $r['recordsFiltered'] = 0;
            $r['data'] = [];
        }
        return response()->json($r);
    }


    public function storeTargeted(TargetedRequest $request)
    {
        $promotionErrors = parent::customValidatePromotionRequest($request);
        $targetedErrors = self::customValidateTargetedRequest($request);
        $errors = array_merge($promotionErrors, $targetedErrors);
        if (!empty($errors)) {
            return redirect()->back()->withInput()->withErrors($errors);
        }

        $inputs = $request->except(['_token', '_method', 'itemsID', 'targetsID',
            'from_age', 'to_age', 'gender', 'from_member', 'to_member', 'job']);
        $inputs['is_promotion'] = false;
        $ads = Ads::create($inputs);

        $this->storeImageAndThumbnail($request, $ads);
        $this->storeArea($request, $ads);
        $this->storeRules($request, $ads);
        $ads->save();
        return redirect()->route('targeted.manager-manage');
    }

    protected function storeRules($request, $ads)
    {
        $inputs = $request->only('from_age', 'to_age', 'gender', 'from_family_members', 'to_family_members',
            'jobs_desc');
        if (!is_null($inputs['jobs_desc']))
            $inputs['jobs_desc'] = implode(',', $inputs['jobs_desc']);
        $rule = new TargetedRule($inputs);
        $ads->targetedRule()->save($rule);
    }

    private static function customValidateTargetedRequest($request)
    {
        $errors = [];
        if (!empty($request->input('from_age')) && !empty($request->input('to_age'))) {
            if ($request->input('from_age') > $request->input('to_age'))
                $errors[] = 'From age must be equal to or less than to age';
        }
        if (!empty($request->input('from_member')) && !empty($request->input('to_member'))) {
            if ($request->input('from_member') > $request->input('to_member'))
                $errors[] = 'From family member must be equal to or less than to family member';
        }
        return $errors;
    }

}