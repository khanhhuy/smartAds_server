<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Ads;

class TargetedRule extends Model {

	protected $fillable = ['from_age', 'to_age', 'gender', 'from_family_members', 'to_family_members',
            'jobs_desc'];

	public function targetedAds() {
		return $this->belongsTo('App\Ads', 'ads_id');
	}
}
