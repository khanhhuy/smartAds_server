<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class BeaconMinor extends Model {

	protected $primaryKey='minor';

    public function beacons()
    {
        return $this->hasMany('App\Beacon','minor');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Category','category_minor','beacon_minor');
    }
}
