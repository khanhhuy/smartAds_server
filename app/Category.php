<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {

    protected $fillable = ['id', 'name', 'parent_id', 'is_leaf', 'is_suitable'];
	public $incrementing=false;

    public function parentCategory()
    {
        return $this->belongsTo('App\Category','parent_id');
    }

}
