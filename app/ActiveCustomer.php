<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ActiveCustomer extends Model {

	protected $fillable = ['id'];
    public $incrementing = false;

    protected $date = ['last_received'];

    public function watchingList()
    {
        return $this->belongsToMany('App\Item','watching_lists','customer_id');
    }

    public function getMinEntranceDiscountValue()
    {
        if ($this->min_entrance_value!=null){
            return $this->min_entrance_value;
        }
        else {
            //TODO Huy: implement save default value;
            return 10000;
        }
    }

    public function getMinEntranceDiscountRate()
    {
        if ($this->min_entrance_rate!=null){
            return $this->min_entrance_rate;
        }
        else {
            //TODO Huy: implement save default rate;
            return 0.2;
        }
    }

    public function getMinDiscountValue()
    {
        if ($this->min_aisle_value!=null){
            return $this->min_aisle_value;
        }
        else {
            //TODO Huy: implement save default value;
            return 4000;
        }
    }

    public function getMinDiscountRate()
    {
        if ($this->min_aisle_rate!=null){
            return $this->min_aisle_rate;
        }
        else {
            //TODO Huy: implement save default rate;
            return 0.1;
        }
    }

    public function receivedAds()
    {
        return $this->belongsToMany('App\Ads','received_ads','customer_id')->withTimestamps()->withPivot('last_received');
    }
}
