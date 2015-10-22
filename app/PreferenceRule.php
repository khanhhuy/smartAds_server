<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class PreferenceRule extends AdsController {

	protected $hidden = ['created_at', 'updated_at'];
	
	public function adsList() {
		return $this->belongsToMany('App\Ads', 'ads_rules', 'rule_id', 'ads_id');
	}
}
