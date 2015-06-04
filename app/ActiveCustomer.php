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

    public function receivedAds()
    {
        return $this->belongsToMany('App\Ads','received_ads','customer_id')->withTimestamps()->withPivot('last_received');
    }
}
