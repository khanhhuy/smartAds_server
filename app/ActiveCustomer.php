<?php namespace App;

use Config;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;

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

    public function blackList()
    {
        return $this->belongsToMany('App\Item', 'black_lists', 'customer_id');
    }

    public function getMinEntranceDiscountValue()
    {
        if ($this->min_entrance_value != null) {
            return $this->min_entrance_value;
        } else {
            return Config::get('promotion-threshold.entrance_value');
        }
    }

    public function getMinEntranceDiscountRate()
    {
        if ($this->min_entrance_rate != null) {
            return $this->min_entrance_rate;
        } else {
            return Config::get('promotion-threshold.entrance_rate');
        }
    }

    public function getMinDiscountValue()
    {
        if ($this->min_aisle_value != null) {
            return $this->min_aisle_value;
        } else {
            return Config::get('promotion-threshold.aisle_value');
        }
    }

    public function getMinDiscountRate()
    {
        if ($this->min_aisle_rate != null) {
            return $this->min_aisle_rate;
        } else {
            return Config::get('promotion-threshold.aisle_rate');
        }
    }

    public function receivedAds()
    {
        return $this->belongsToMany('App\Ads', 'received_ads', 'customer_id')->withTimestamps()->withPivot('last_received');
    }
}
