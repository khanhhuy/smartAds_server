<?php namespace App\Http\Controllers;

use App\Ads;
use App\Http\Requests;
use App\Http\Requests\TargetedRequest;
use App\Repositories\CustomerRepositoryInterface;
use App\TargetedRule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Lang;
use Laracasts\Flash\Flash;
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
        $rule = self::createDefaultRule();
        $jobs = $this->customerRepo->getAllJobDesc();
        return view('ads.targeted.create')->with(compact(['rule', 'ads', 'jobs']));
    }

    public function edit(Ads $ads)
    {
        $rule = $ads->targetedRule()->get();

        if (!$rule->isEmpty()) {
            $rule = $rule[0];
            $this->formatRuleFromDB($rule);
        } else
            $rule = self::createDefaultRule();

        $jobs = $this->customerRepo->getAllJobDesc();

        return view('ads.targeted.edit')->with(compact(['rule', 'ads', 'jobs']));
    }

    private function formatRuleFromDB($rule)
    {
        if ($rule->to_age == 0) {
            $rule->to_age = '';
            if ($rule->from_age == 0)
                $rule->from_age = '';
        }
        if ($rule->to_family_members == 0) {
            $rule->to_family_members = '';
            if ($rule->from_family_members == 0)
                $rule->from_family_members = '';
        }
        if ($rule->jobs_desc !== null) {
            $rule->jobs_desc = explode(',', $rule->jobs_desc);
        }
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
                        $val = trim($val);
                        $filtered = $filtered->whereRaw("target_customers_display LIKE ?", ["%$val%"]);
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
                        $filtered->skip($request->input('start'))->take($request->input('length'))->orderBy('updated_at', 'desc');
                        self::eagerLoadTargetedRelations($filtered);
                        $displayAds = $filtered->get();
                        break;
                    default:
                        $filtered->skip($request->input('start'))->take($request->input('length'))
                            ->orderBy($orderColumn, $order[0]['dir']);
                        self::eagerLoadTargetedRelations($filtered);
                        $displayAds = $filtered->get();
                        break;
                }
            } else {
                $filtered->skip($request->input('start'))->take($request->input('length'))->orderBy('updated_at', 'desc');
                self::eagerLoadTargetedRelations($filtered);
                $displayAds = $filtered->get();
            }

            //transform
            $r['data'] = $displayAds->map(function ($ads) {
                return [
                    $ads->id,
                    $ads->title,
                    Utils::formatTargets($ads->targets),
                    $ads->target_customers_display,
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

    private function validateTargeted($request)
    {
        $promotionErrors = parent::customValidatePromotionRequest($request);
        $targetedErrors = self::customValidateTargetedRequest($request);
        $errors = array_merge($promotionErrors, $targetedErrors);
        return $errors;
    }

    public function storeTargeted(TargetedRequest $request)
    {
        $errors = $this->validateTargeted($request);
        if (!empty($errors)) {
            return redirect()->back()->withInput()->withErrors($errors);
        }

        $inputs = $request->except(['_token', '_method', 'itemsID', 'targetsID',
            'from_age', 'to_age', 'gender', 'from_family_members', 'to_family_members', 'job']);
        $inputs['is_promotion'] = false;

        $ads = Ads::create($inputs);

        $this->storeImageAndThumbnail($request, $ads);
        $this->storeArea($request, $ads);
        $this->storeRules($request, $ads);
        $ads->save();
        return redirect()->route('targeted.manager-manage');
    }

    public function updateTargeted(Ads $ads, TargetedRequest $request)
    {
        $errors = $this->validateTargeted($request);
        if (!empty($errors)) {
            return redirect()->back()->withInput()->withErrors($errors);
        }

        $inputs = $request->except(['_token', '_method', 'itemsID', 'targetsID',
            'from_age', 'to_age', 'gender', 'from_family_members', 'to_family_members', 'job',
            'provide_image_link', 'image_url','provide_thumbnail_link','thumbnail_url']);
        if (!$request->has('is_whole_system')) {
            $inputs['is_whole_system'] = false;
        }
        if (!$request->has('auto_thumbnail')) {
            $inputs['auto_thumbnail'] = false;
        }
        $ads->update($inputs);
        $ads->areas()->detach();
        $ads->stores()->detach();

        //targets
        $this->storeArea($request, $ads);
        $this->storeRules($request, $ads);

        //image + thumbnail
        $this->updateImageAndThumbnail($ads, $request);

        $ads->save();

        Flash::success(Lang::get('flash.edit_success'));
        return redirect()->route('targeted.manager-manage');
    }

    protected function storeRules($request, $ads)
    {
        $inputs = $request->only('from_age', 'to_age', 'gender', 'from_family_members', 'to_family_members',
            'jobs_desc');
        if (!is_null($inputs['jobs_desc']))
            $inputs['jobs_desc'] = implode(',', $inputs['jobs_desc']);
        $rule = new TargetedRule($inputs);

        TargetedRule::where('ads_id', $ads->id)->delete();
        $ads->targetedRule()->save($rule);
        $ads->target_customers_display = Utils::formatRules($rule);
    }

    private static function customValidateTargetedRequest($request)
    {
        $errors = [];
        if (!empty($request->input('from_age')) && !empty($request->input('to_age'))) {
            if ($request->input('from_age') > $request->input('to_age'))
                $errors[] = 'From age must be equal to or less than to age';
        }
        if (!empty($request->input('from_family_members')) && !empty($request->input('to_family_members'))) {
            if ($request->input('from_family_members') > $request->input('to_family_members'))
                $errors[] = 'From family member must be equal to or less than to family member';
        }
        return $errors;
    }

    public static function createDefaultRule()
    {
        $rule = new TargetedRule(['from_age' => '', 'to_age' => '',
            'gender' => '2', 'from_family_members' => '', 'to_family_members' => '',
            'jobs_desc' => null]);
        return $rule;
    }

    public function eagerLoadTargetedRelations($query)
    {
        $query->with('stores')->with('areas');
    }
}