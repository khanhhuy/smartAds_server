<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Ads;

class TargetedRule extends Model {

	public function targetedAds() {
		return $this->belongsTo('App\Ads', 'ads_id');
	}
}
