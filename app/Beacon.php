<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Beacon extends Model {

	protected $primaryKey=['major','minor'];
    public $incrementing=false;
}
