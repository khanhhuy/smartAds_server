<?php namespace App;

use App\Facades\Connector;
use Illuminate\Database\Eloquent\Model;

class Store extends Model {

    public function ads()
    {
        return $this->belongsToMany('App\Ads');
    }

    public function area()
    {
        return $this->belongsTo('App\Area');
    }

}
