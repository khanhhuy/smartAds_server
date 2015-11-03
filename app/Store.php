<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model {
    protected $fillable = ['id', 'name', 'address', 'area_id', 'display_area'];

    public function ads()
    {
        return $this->belongsToMany('App\Ads');
    }

    public function area()
    {
        return $this->belongsTo('App\Area');
    }

}
