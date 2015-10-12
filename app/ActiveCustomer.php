<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class ActiveCustomer extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    protected $fillable = ['id'];
    public $incrementing = false;

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['remember_token'];

    protected $date = ['last_received'];

    public function watchingList()
    {
        return $this->belongsToMany('App\Item', 'watching_lists', 'customer_id');
    }

    public function getMinEntranceDiscountValue()
    {
        if ($this->min_entrance_value != null) {
            return $this->min_entrance_value;
        } else {
            //TODO Huy: implement save default value;
            return 10000;
        }
    }

    public function getMinEntranceDiscountRate()
    {
        if ($this->min_entrance_rate != null) {
            return $this->min_entrance_rate;
        } else {
            //TODO Huy: implement save default rate;
            return 20;
        }
    }

    public function getMinDiscountValue()
    {
        if ($this->min_aisle_value != null) {
            return $this->min_aisle_value;
        } else {
            //TODO Huy: implement save default value;
            return 4000;
        }
    }

    public function getMinDiscountRate()
    {
        if ($this->min_aisle_rate != null) {
            return $this->min_aisle_rate;
        } else {
            //TODO Huy: implement save default rate;
            return 10;
        }
    }

    public function receivedAds()
    {
        return $this->belongsToMany('App\Ads', 'received_ads', 'customer_id')->withTimestamps()->withPivot('last_received');
    }
}
