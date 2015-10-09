<?php namespace App\Http\Controllers;

use App\ActiveCustomer;
use App\Ads;
use App\Area;
use App\Http\Requests\PromotionRequest;
use App\Item;
use App\Repositories\ItemRepositoryInterface;
use Request;


class AdsController extends Controller
{
    protected $itemRepo;

    public function __construct(ItemRepositoryInterface $itemRepo)
    {
        $this->itemRepo = $itemRepo;
    }

    public function show(Ads $ads)
    {
//        return view('ads.show.' . $ads->id, compact('ads'));
        if ($ads->image_display) {
            return view('ads.ads-master')->with(compact('ads'));
        } else {
            return redirect($ads->web_url);
        }
    }

    public function index(Request $request)
    {
        $idOnly = $request->input('id_only');
        if ($idOnly != null && $idOnly == true) {
            return Ads::lists('id');
        } else {
            return Ads::all();
        }
    }

    public function receivedIndex(Request $request, ActiveCustomer $customer)
    {
        define('LIMIT_DEFAULT', 25);
        $limit = $request->input('limit', LIMIT_DEFAULT);
        if ($limit < 1) {
            $limit = LIMIT_DEFAULT;
        }
        $result = $customer->receivedAds()->latest('last_received')->take($limit)->get();

        $idOnly = Request::input('id_only');
        if ($idOnly != null && $idOnly == true) {
            return $result->lists('id');
        } else {
            return $result;
        }

    }

    public function manage()
    {
        return view('ads.manage');
    }

    public function createPromotion()
    {
        $ads=new Ads;
        $items=[];
        return view('ads.promotions.create')->with(compact(['ads','items']));
    }

    public function storePromotion(PromotionRequest $request)
    {
        if ($request->input('start_date') > $request->input('end_date')) {
            return redirect()->back()->withInput()->withErrors('Start Date must be before End Date');
        }
        if ($request->input('image_display')) {
            if ($request->input('provide_image_link')) {
                if (empty($request->input('image_url'))) {
                    return redirect()->back()->withInput()->withErrors('Image URL is required');
                } else {
                    $ads = self::createPromotionFromRequest($request);
                }
            } else {
                if (!($request->hasFile('image_file'))) {
                    return redirect()->back()->withInput()->withErrors('Image File is required');
                } else {
                    $ads = self::createPromotionFromRequest($request);
                    $image = $request->file('image_file');
                    $fullSaveFileName = $ads->id . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('/img/ads'), $fullSaveFileName);
                    $ads->image_url = ('/img/ads/' . $fullSaveFileName);
                }
            }
        } else {
            $ads = self::createPromotionFromRequest($request);
        }


        $itemsID = $request->input('itemsID');
        foreach ($itemsID as $itemID) {
            Item::firstOrCreate(['id' => $itemID]);
        }
        $ads->items()->attach($itemsID);
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
        return 'success';
    }

    private static function createPromotionFromRequest($request)
    {
        $inputs = $request->except(['_token', 'itemsID', 'targetsID']);
        $inputs['discount_rate'] /= 100.0;
        $inputs['is_promotion'] = true;

        return Ads::create($inputs);
    }

    public function edit(Ads $ads)
    {
        if ($ads->is_promotion) {
            $items1 = $ads->items;
            foreach ($items1 as $item) {
                $items[$item->id] = $this->itemRepo->getItemNameByID($item->id) . " [" . $item->id . "]";
            }
            return view('ads.promotions.edit')->with(compact(['items', 'ads']));
        } else {
            return 'TODO Khanh Huy: Edit Targeted Ads';
        }
    }

    public function update(Ads $ads)
    {

    }
}
