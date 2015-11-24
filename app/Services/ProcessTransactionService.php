<?php
namespace App\Services;

use App\ActiveCustomer;
use App\Category;
use App\Facades\Connector;
use App\Item;
use Carbon\Carbon;
use Setting;

class ProcessTransactionService
{
    public function __construct()
    {
    }

    public function processAllCustomer($fromDate = null, $toDate = null){
        $allCustomer = ActiveCustomer::all();
        $allWatchingList = array();

        foreach ($allCustomer as $index => $customer) {

            $watchingList = self::processCustomer($customer, $fromDate, $toDate);
            //for testing
            $allWatchingList[$index]['customer'] = $customer->id;
            $allWatchingList[$index]['items'] = $watchingList;

        }

        return $allWatchingList;
    }

    private function createMockItem($itemID) {
        $item = Item::find($itemID);
        if ($item === null)
            Item::create(['id' => $itemID]);
    }

    public function processCustomer(ActiveCustomer $customer, $fromDate = null, $toDate = null) {

        $transactions = Connector::getShoppingHistoryFromCustomer($customer->id, $fromDate, $toDate);
        $watchingList = [];
        $blackList = $customer->blackList()->lists('id');

        if (empty($transactions)) {
            return $watchingList;
        }

        //remove blacklist item
        foreach ($transactions as $key => $eachTrans) {
            if (in_array($eachTrans['item_id'], $blackList))
            {
                unset($transactions[$key]);
            }
        }

        //check if item belongs to suitable categories
        foreach ($transactions as $key => $eachTrans) {
            $cat = Connector::getCategoryFromItemID($eachTrans['item_id']);
            if(!Category::find($cat->id)['is_suitable']) {
                unset($transactions[$key]);
            }
        }

        //mining
        $transactions = array_values($transactions);
        $i = 0;
        $useRelatedItem = Setting::get('process-config.related-item');
        if ($useRelatedItem === null)
            $useRelatedItem = false;

        while($i < count($transactions)) {
            $j = $i + 1;
            $count = 1;
            if ($useRelatedItem)
                $relatedItems = Connector::getRelatedItem($transactions[$i]['item_id']);
            $isAddedRelated = false;
            while ($j < count($transactions)) {
                if ($transactions[$i]['item_id'] == $transactions[$j]['item_id']) {
                    $count++;
                    unset($transactions[$j]);
                    $transactions = array_values($transactions);
                    $j--;
                } elseif ($useRelatedItem && !$isAddedRelated && ($relatedItems !== null)) {
                    foreach ($relatedItems as $item) {
                        if ($transactions[$j]['item_id'] == $item['id']) {
                            $count++;
                            break;
                        }
                    }
                }
                if ($count == 2) {
                    if (!in_array($transactions[$i]['item_id'], $watchingList)) {
                        $watchingList[] = $transactions[$i]['item_id'];
                        $this->createMockItem($transactions[$i]['item_id']);
                    }
                    if ($useRelatedItem && !$isAddedRelated && ($relatedItems !== null)) {
                        foreach ($relatedItems as $item) {
                            if (!in_array($item['id'], $watchingList) && 
                                !in_array($item['id'], $blackList)) {
                                    $cat = Connector::getCategoryFromItemID($item['id']);
                                    if(Category::find($cat->id)['is_suitable']) {
                                        $watchingList[] = $item['id'];
                                        $this->createMockItem($item['id']);
                                    }
                            }
                        }        
                        $isAddedRelated = true;
                    }
                }
                $j++;
            }
            $i++;
        }

        $customer->watchingList()->detach();
        $customer->watchingList()->attach($watchingList);
        $customer->last_mining = Carbon::now();
        $customer->save();

        return $watchingList;
    }
}