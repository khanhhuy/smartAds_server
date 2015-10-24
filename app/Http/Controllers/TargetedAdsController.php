<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\AdsController;
use Utils;
use App\Facades\Connector;
use App\Http\Requests\TargetedRequest;
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

    public function storeTargeted(TargetedRequest $request)
    {   
        dd($request);
        $promotionErrors = parent::customValidatePromotionRequest($request);
        $targetedErrors = self::customValidateTargetedRequest($request);
        $errors = array_merge($promotionErrors, $targetedErrors);
        if (!empty($errors)) {
            return redirect()->back()->withInput()->withErrors($errors);
        }

        return redirect()->route('targeted.create');

        $ads = self::createPromotionFromRequest($request);

        //image upload
        if ($request->input('image_display')) {
            if (!$request->input('provide_image_link')) {
                $image = $request->file('image_file');
                $fullSaveFileName = $ads->id . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('/img/ads'), $fullSaveFileName);
                $ads->image_url = ('/img/ads/' . $fullSaveFileName);
            }
        }

        //thumbnail
        if (!$request->has('auto_thumbnail')) {
            if (!$request->input('provide_thumbnail_link')) {
                if ($request->hasFile('thumbnail_file')) {
                    $thumbnail = $request->file('thumbnail_file');
                    $fullSaveFileName = $ads->id . '.' . $thumbnail->getClientOriginalExtension();
                    $thumbnail->move(public_path('/img/thumbnails'), $fullSaveFileName);
                    $ads->thumbnail_url = ('/img/thumbnails/' . $fullSaveFileName);
                }
            }
        } elseif ($request->input('auto_thumbnail') && $request->input('image_display')) {
            $provide_image_link = $request->input('provide_image_link');
            $adsID = $ads->id;
            $ext = 'png';
            $image_url = '';
            if (!$provide_image_link) {
                $ext = $image->getClientOriginalExtension();
            } else {
                $image_url = $request->input('image_url');
            }
            Queue::push(function ($job) use ($provide_image_link, $adsID, $ext, $image_url) {
                if (!$provide_image_link) {
                    Utils::createThumbnail($adsID, $ext, public_path('img/ads') . "/$adsID" . ".$ext");
                } else {
                    $ext = Utils::createThumbnailFromURL($image_url, $adsID);
                }
                $ads = Ads::find($adsID);
                $ads->thumbnail_url = ('/img/thumbnails/' . $adsID . '.' . $ext);
                $ads->save();

                $job->delete();
            });
            $ads->thumbnail_url = ('/img/thumbnails/' . $ads->id . '.png');
        }

        //items
        $itemsID = $request->input('itemsID');
        foreach ($itemsID as $itemID) {
            Item::firstOrCreate(['id' => $itemID]);
        }
        $ads->items()->attach($itemsID);

        //targets
        if (!$request->has('is_whole_system') || !$request->input('is_whole_system')) {
            $targetsID = $request->input('targetsID');
            if (!empty($targetsID)) {
                foreach ($targetsID as $targetID) {
                    $a = Area::find($targetID);
                    if (!empty($a)) {
                        $ads->areas()->attach($targetID);
                    } else {
                        $ads->stores()->attach($targetID);
                    }
                }
            }
        }
        $ads->save();
        return redirect()->route('promotions.manager-manage');
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