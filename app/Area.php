<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Area extends Model {

    public function ads()
    {
        return $this->belongsToMany('App\Ads');
    }

    public function parentArea()
    {
        return $this->belongsTo('App\Area','parent_id');
    }
    public function getApplyingAdsForCustomer(ActiveCustomer $customer)
    {
        $allAds=$this->ads()->forCustomer($customer)->get();
        $a=$this;
        while ($a->parentArea!=null){
            $a=$a->parentArea;
            $allAds=$allAds->merge($a->ads()->forCustomer($customer)->get());
        }
        return $allAds;
    }

}
