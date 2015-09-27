<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {

    protected $fillable = ['id'];
	public $incrementing=false;

    public function parentCategory()
    {
        return $this->belongsTo('App\Category','parent_id');
    }
}
