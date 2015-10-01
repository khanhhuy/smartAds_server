<?php
namespace App\Services;

use App\ActiveCustomer;
use App\Category;
use App\Facades\Connector;
use Carbon\Carbon;
use App\Item;

class MiningSimpleService
{
    public function __construct()
    {
    }

    public function miningAllCustomer($fromDate = null, $toDate = null){

        $allCustomer = ActiveCustomer::all();
        $allWatchingList = array();

        foreach ($allCustomer as $index => $customer) {

            $watchingList = self::miningCustomer($customer, false, $fromDate, $toDate);

            //for testing
            $allWatchingList[$index]['customer'] = $customer->id;
            $allWatchingList[$index]['items'] = $watchingList;

        }

        return $allWatchingList;
    }

    public function miningCustomer(ActiveCustomer $customer, $isContinue = false,
                                                $fromDate = null, $toDate = null) {

        $transactions = Connector::getShoppingHistoryFromCustomer($customer, $fromDate, $toDate);
        $watchingList = array();

        if (empty($transactions)) {
            return $watchingList;
        }

        //remove duplicate
        foreach ($transactions as $key => $eachTrans) {
            if(!in_array($eachTrans['item_id'], $watchingList)) {
                $watchingList[] = $eachTrans['item_id'];
            }
        }

        //check if item belongs to suitable categories
        foreach ($watchingList as $key => $item) {
            $catID = Connector::getCategoryIDFromItemID($item);
            if(!Category::find($catID)['is_suitable']) {
                unset($watchingList[$key]);
            }
            elseif (Item::find($item) == null) {
                Item::create(['id'=>$item]);
            }
        }


        if (!$isContinue) {
            //remove old watching list and replace with the new one
            $customer->watchingList()->detach();
        }
        else {
            //add new item to current watching list
            $currentWatchingList = $customer->watchingList();
            foreach ($watchingList as $key => $item) {
                if (in_array($item, $currentWatchingList)){
                    unset($watchingList[$key]);
                }
            }
        }

        $customer->watchingList()->attach($watchingList);
        $customer->last_mining = Carbon::now();
        $customer->save();

        return $watchingList;
    }
}