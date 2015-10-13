<?php namespace App\Http\Controllers;

use App\ActiveCustomer;
use App\Ads;
use App\Area;
use App\Http\Requests\PromotionRequest;
use App\Item;
use App\Repositories\ItemRepositoryInterface;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Utils;


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

    public function managePromotions()
    {
        return view('ads.manage');
    }

    public function createPromotion()
    {
        $ads = new Ads;
        $items = [];
        return view('ads.promotions.create')->with(compact(['ads', 'items']));
    }

    public function storePromotion(PromotionRequest $request)
    {
        $errors = self::customValidatePromotionRequest($request);
        if (!empty($errors)) {
            return redirect()->back()->withInput()->withErrors($errors);
        }

        $ads = self::createPromotionFromRequest($request);
        if ($request->input('image_display')) {
            if (!$request->input('provide_image_link')) {
                $image = $request->file('image_file');
                $fullSaveFileName = $ads->id . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('/img/ads'), $fullSaveFileName);
                $ads->image_url = ('/img/ads/' . $fullSaveFileName);
            }
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
        return redirect()->route('promotions.manager-manage');
    }

    private static function customValidatePromotionRequest($request)
    {
        $errors = [];
        if ($request->input('start_date') > $request->input('end_date')) {
            $errors[] = 'Start Date must be before End Date';
        }
        if ($request->input('image_display')) {
            if ($request->input('provide_image_link')) {
                if (empty($request->input('image_url'))) {
                    $errors[] = 'Image URL is required';
                }
            } elseif (!($request->hasFile('image_file'))) {
                $errors[] = 'Image File is required';
            }
        }
        return $errors;
    }

    private static function createPromotionFromRequest($request)
    {
        $inputs = $request->except(['_token', '_method', 'itemsID', 'targetsID']);
        $inputs['is_promotion'] = true;

        return Ads::create($inputs);
    }

    public function edit(Ads $ads)
    {
        if ($ads->is_promotion) {
            $items1 = $ads->items;
            foreach ($items1 as $item) {
                $items[$item->id] = Utils::formatItem($this->itemRepo->getItemNameByID($item->id), $item->id);
            }
            return view('ads.promotions.edit')->with(compact(['items', 'ads']));
        } else {
            return 'TODO Khanh Huy: Edit Targeted Ads';
        }
    }

    public function updatePromotion(Ads $ads, PromotionRequest $request)
    {
        $errors = self::customValidatePromotionRequest($request);
        if (!empty($errors)) {
            return redirect()->back()->withInput()->withErrors($errors);
        }
        $inputs = $request->except(['_token', '_method', 'itemsID', 'targetsID', 'provide_image_link', 'image_url']);
        if (!$request->has('is_whole_system')) {
            $inputs['is_whole_system'] = false;
        }
        $ads->update($inputs);
        $ads->areas()->detach();
        $ads->stores()->detach();

        //items
        $itemsID = $request->input('itemsID');
        foreach ($itemsID as $itemID) {
            Item::firstOrCreate(['id' => $itemID]);
        }
        $ads->items()->sync($itemsID);

        //targets
        if (!$inputs['is_whole_system']) {
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

        //image
        if ($request->input('image_display')) {
            if (!$request->input('provide_image_link')) {
                $ads->provide_image_link = false;
                $image = $request->file('image_file');
                $fullSaveFileName = $ads->id . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('/img/ads'), $fullSaveFileName);
                $ads->image_url = ('/img/ads/' . $fullSaveFileName);
            } else {
                if ($ads->provide_image_link) {
                    $ads->image_url = $request->input('image_url');
                } else {
                    if ($ads->image_url !== $request->input('image_url')) {
                        $ads->provide_image_link = true;
                        $ads->image_url = $request->input('image_url');
                    }
                }
            }
        }

        $ads->save();
        return redirect()->route('promotions.manager-manage');
    }

    public function table(Request $request)
    {
        $allPromotions = Ads::promotion();
        $r['draw'] = (int)$request->input('draw');
        $r['recordsTotal'] = $allPromotions->count();
        $r['recordsFiltered'] = $r['recordsTotal'];
        $displayPromotions = $allPromotions->take($request->input('length'))->orderBy('updated_at','asc')->get();
        $itemIDs = DB::table('ads_item')->whereIn('ads_id', $displayPromotions->lists('id'))->distinct()->lists('item_id');
        $itemNames = $this->itemRepo->getItemNamesByIDs($itemIDs);
        $r['data'] = $displayPromotions->map(function ($ads) use ($itemNames) {
            return [
                $ads->id,
                $ads->items->map(function ($item) use ($itemNames) {
                    return Utils::formatItem($itemNames[$item->id], $item->id);
                }),
                Utils::formatTargets($ads->targets),
                Carbon::parse($ads->getOriginal('start_date'))->format('m-d-Y'),
                Carbon::parse($ads->getOriginal('end_date'))->format('m-d-Y'),
                ((float)$ads->discount_rate) . ' %',
                (float)$ads->discount_value,
                $ads->updated_at->format('m-d-Y'),
            ];
        });
        return response()->json($r);
    }

    public function deleteMulti(Request $request)
    {
        $ids=$request->input('ids');
        if (empty($ids)){
            return abort('400');
        }
        Ads::destroy($ids);
    }

    public function thumbnail($ads) {
        return redirect('img/thumbnails/'.$ads->id.'.png');
    }
}
