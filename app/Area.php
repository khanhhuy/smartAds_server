<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Area extends Model {
    protected $fillable = ['id', 'name', 'parent_id'];
    public function ads()
    {
        return $this->belongsToMany('App\Ads');
    }

    public function parentArea()
    {
        return $this->belongsTo('App\Area','parent_id');
    }

    public function getApplyingPromotionsForCustomer(ActiveCustomer $customer)
    {
        $allAds = $this->ads()->promotions()->forCustomer($customer)->get();
        $a=$this;
        while ($a->parentArea !== null) {
            $a=$a->parentArea;
            $allAds = $allAds->merge($a->ads()->promotions()->forCustomer($customer)->get());
        }
        return $allAds;
    }

}
