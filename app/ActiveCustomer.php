<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ActiveCustomer extends Model {

	protected $fillable = ['id'];
    public $incrementing = false;

    public function watchingList()
    {
        return $this->belongsToMany('App\Item','watching_lists','customer_id');
    }
}
