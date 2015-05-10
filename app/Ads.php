<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Ads extends Model {

    protected $visible = ['id', 'title'];

    public function items()
    {
        return $this->belongsToMany('App\Item');
	}

    public function categories()
    {
        return $this->belongsToMany('App\Category');
    }

}
