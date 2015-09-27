<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class BeaconMajor extends Model {

    protected $primaryKey='major';

    public function store()
    {
        return $this->belongsTo('App\Store');
	}

}
