<?php namespace App\Http\Controllers;

use App\ActiveCustomer;
use App\Ads;
use App\Area;
use App\Http\Requests\PromotionRequest;
use App\Item;
use App\Repositories\ItemRepositoryInterface;
use App\Store;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Lang;
use Laracasts\Flash\Flash;
use Queue;
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
            if ($ads->is_promotion) {
                $start_date = Carbon::parse($ads->start_date)->format('d/m/Y');
                $end_date = Carbon::parse($ads->end_date)->format('d/m/Y');
                $itemName = $this->itemRepo->getItemNameByID($ads->items[0]->id);
                return view('ads.show.promotion-master')->with(compact('ads', 'start_date', 'end_date', 'itemName'));
            } else {
                return view('ads.show.targeted-master')->with(compact('ads'));
            }
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
        return view('ads.promotions.manage');
    }


    public function manageTargeted(Request $request)
    {
        return view('ads.targeted.manage');
    }

    public function createPromotion()
    {
        $ads = new Ads;
        $items = [];
        return view('ads.promotions.create')->with(compact(['ads', 'items']));
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

    public function storePromotion(PromotionRequest $request)
    {
        $errors = self::customValidatePromotionRequest($request);
        if (!empty($errors)) {
            return redirect()->back()->withInput()->withErrors($errors);
        }

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
        Flash::success(Lang::get('flash.add_success'));
        return redirect()->route('promotions.manager-manage');
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
        if (!$request->has('auto_thumbnail')) {
            $inputs['auto_thumbnail'] = false;
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
            $ext = 'png';
            $provide_image_link = $request->input('provide_image_link');
            $adsID = $ads->id;
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

            $ads->thumbnail_url = ('/img/thumbnails/' . $ads->id . '.' . $ext);
        }

        $ads->save();

        Flash::success(Lang::get('flash.edit_success'));
        return redirect()->route('promotions.manager-manage');
    }

    protected static function customValidatePromotionRequest($request)
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

    public function promotionsTable(Request $request)
    {
        $PROMOTIONS_COLUMNS = ['id', 'items', 'areas', 'start_date', 'end_date', 'discount_rate', 'discount_value', 'updated_at'];
        $allPromotions = Ads::promotions();
        $r['draw'] = (int)$request->input('draw');
        $r['recordsTotal'] = $allPromotions->count();
        $filtered = $allPromotions;
        $joinedAdsItem = false;

        //search
        $noResult = false;
        $cols = $request->input("columns");

        //filter item first
        $val = $cols[2]['search']['value'];
        if (!empty($val)) {
            $rItems = $this->itemRepo->searchItemsGetIDs($val);
            if (!empty($rItems)) {
                $filtered = $filtered->join('ads_item', 'ads.id', '=', 'ads_item.ads_id')->whereIn('item_id', $rItems);
            }
            $joinedAdsItem = true;
        }
        for ($c = 1; $c < 8; $c++) {
            $val = $cols[$c]['search']['value'];
            if (!empty($val)) {
                switch ($PROMOTIONS_COLUMNS[$c - 1]) {
                    case 'id':
                        $filtered = $filtered->whereRaw("id LIKE ?", ["$val%"]);
                        break;
                    case 'items':
                        //already done
                        break;
                    case 'areas':
                        $val = trim($val);
                        $includeWholeSystem = false;
                        $filteredAreas = [];
                        $filteredStores = [];
                        $words = preg_split("/ ( |,) /", $val);
                        if (empty($words)) {
                            break;
                        }

                        for ($i = 0; $i < count($words); $i++) {
                            $w = $words[$i];
                            if (in_array(strtolower($w), ['a', 'l', 'al', 'll', 'all'])) {
                                $includeWholeSystem = true;
                            }
                            $filteredAreas[$i] = Area::whereRaw('name LIKE ?', ["%$w%"])->lists('id');
                            $filteredStores[$i] = Store::whereRaw('name LIKE ?', ["%$w%"])->lists('id');
                            if (empty($filteredAreas[$i]) && empty($filteredStores[$i])) {
                                $noResult = true;
                                break;
                            }
                        }
                        if ($noResult) {
                            if (count($words) == 1 && $includeWholeSystem) {
                                $filtered->where('is_whole_system', true);
                                $noResult = false;
                            }
                            break;
                        }
                        $filtered->leftJoin('ads_store', 'ads.id', '=', 'ads_store.ads_id')
                            ->leftJoin('ads_area', 'ads.id', '=', 'ads_area.ads_id');
                        $filtered->where(function ($filtered) use ($words, $filteredStores, $filteredAreas, $includeWholeSystem) {
                            for ($i = 0; $i < count($words); $i++) {
                                $filtered->where(function ($query) use ($i, $filteredStores, $filteredAreas) {
                                    if (!empty($filteredAreas[$i])) {
                                        $query->whereIn('area_id', $filteredAreas[$i]);
                                        if (!empty($filteredStores[$i])) {
                                            $query->orwhereIn('store_id', $filteredStores[$i]);
                                        }
                                    } else {
                                        $query->whereIn('store_id', $filteredStores[$i]);
                                    }
                                });
                            }

                            if ($includeWholeSystem) {
                                $filtered->orWhere('is_whole_system', true);
                            }
                        });
                        break;
                    default:
                        break;
                }
            }
        }
        if (!$noResult) {
            $r['recordsFiltered'] = $filtered->count();
            //order
            $itemNames = [];
            $useDefaultOrder = false;
            if ($request->has('order')) {
                $order = $request->input('order');
                if ($order[0]['column'] <= 0) {
                    $useDefaultOrder = true;
                } else {
                    $orderColumn = $PROMOTIONS_COLUMNS[$order[0]['column'] - 1];
                    switch ($orderColumn) {
                        case 'items':
                            $temp = clone $filtered;
                            if ($joinedAdsItem) {
                                $itemIDs = $temp->distinct()->lists('item_id');
                            } else {
                                $itemIDs = $temp->join('ads_item', 'ads.id', '=', 'ads_item.ads_id')->distinct()->lists('item_id');
                            }
                            if (empty($itemIDs)) {
                                $displayPromotions = $filtered->skip($request->input('start'))->take($request->input('length'))
                                    ->orderBy('updated_at', 'desc')->get();
                                break;
                            }
                            $itemNames = $this->itemRepo->getItemNamesByIDs($itemIDs);
                            $filtered = $filtered->get();
                            foreach ($filtered as $p) {
                                $pItemIDs = $p->items()->lists('id');
                                if (empty($pItemIDs)) {
                                    $p->minItemName = null;
                                } else {
                                    $p->minItemName = $itemNames[$pItemIDs[0]];
                                }
                            }
                            if ($order[0]['dir'] == 'asc') {
                                $filtered->sort(function ($p1, $p2) {
                                    return strcmp($p1->minItemName, $p2->minItemName);
                                });
                            } else {
                                $filtered->sort(function ($p1, $p2) {
                                    return -strcmp($p1->minItemName, $p2->minItemName);
                                });
                            }

                            $displayPromotions = $filtered->slice($request->input('start'), $request->input('length'));
                            break;
                        case 'areas':
                            $displayPromotions = Utils::sortByAreasThenSlice($filtered, $order[0]['dir'],
                                $request->input('start'), $request->input('length'));
                            break;
                        default:
                            $displayPromotions = $filtered->skip($request->input('start'))->take($request->input('length'))
                                ->orderBy($orderColumn, $order[0]['dir'])->get();
                            break;
                    }
                }
            } else {
                $useDefaultOrder = true;
            }
            if ($useDefaultOrder) {

                $displayPromotions = $filtered->skip($request->input('start'))->take($request->input('length'))
                    ->orderBy('updated_at', 'desc')->get();
            }

            if (empty($itemNames)) {
                $itemIDs = DB::table('ads_item')->whereIn('ads_id', $displayPromotions->lists('id'))->distinct()->lists('item_id');
                $itemNames = $this->itemRepo->getItemNamesByIDs($itemIDs);
            }


            $r['data'] = $displayPromotions->map(function ($ads) use ($itemNames) {
                return [
                    $ads->id,
                    $ads->items->map(function ($item) use ($itemNames) {
                        return Utils::formatItem($itemNames[$item->id], $item->id);
                    }),
                    Utils::formatTargets($ads->targets),
                    Utils::formatDisplayDate($ads->getOriginal('start_date')),
                    Utils::formatDisplayDate($ads->getOriginal('end_date')),
                    ((float)$ads->discount_rate) . ' %',
                    (float)$ads->discount_value,
                ];
            });
        } else {
            $r['recordsFiltered'] = 0;
            $r['data'] = [];
        }

        return response()->json($r);
    }

    public function targetedTable(Request $request)
    {
        $TARGETED_ADS_COLUMNS = ['id', 'title', 'areas', 'targeted_customers', 'start_date', 'end_date', 'updated_at'];
        $allTargeted = Ads::targeted();
        $r['draw'] = (int)$request->input('draw');
        $r['recordsTotal'] = $allTargeted->count();
        $r['recordsFiltered'] = $r['recordsTotal'];
        if ($request->has('order')) {
            $order = $request->input('order');
            $orderColumn = $TARGETED_ADS_COLUMNS[$order[0]['column'] - 1];
            switch ($orderColumn) {
                case 'areas':
                    $displayPromotions = Utils::sortByAreasThenSlice($allTargeted, $order[0]['dir'],
                        $request->input('start'), $request->input('length'));
                    break;
                case 'targeted_customers':
                    //TODO Huy: sort by targeted customers
                    break;
                default:
                    $displayPromotions = $allTargeted->skip($request->input('start'))->take($request->input('length'))
                        ->orderBy($orderColumn, $order[0]['dir'])->get();
                    break;
            }
        } else {
            $displayPromotions = $allTargeted->skip($request->input('start'))->take($request->input('length'))->orderBy('updated_at', 'asc')->get();
        }
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

    public function deleteMulti(Request $request)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return abort('400');
        }
        Ads::destroy($ids);
    }

    public function thumbnail($ads)
    {
        if (!empty($ads->thumbnail_url)) {
            return redirect($ads->thumbnail_url);
        } else {
            return null;
        }
    }

}
