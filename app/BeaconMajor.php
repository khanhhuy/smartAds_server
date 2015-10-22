<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class BeaconMajor extends Model {

    protected $primaryKey='major';
    protected $fillable=['major','store_id'];

    public function store()
    {
        return $this->belongsTo('App\Store');
	}

}
