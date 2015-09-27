<?php namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Ads extends Model
{

    protected $visible = ['id', 'title','minors'];

    public function items()
    {
        return $this->belongsToMany('App\Item');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Category');
    }

    public function scopeAvailable($query)
    {
        return $query->where(function ($query) {
            $query->where('start_date', '<=', Carbon::now())->orWhereNull('start_date');
        })->where('end_date', '>=', new Carbon());
    }

    public function scopeForCustomer($query, ActiveCustomer $customer)
    {
        $minValue = $customer->getMinDiscountValue();
        $minRate = $customer->getMinDiscountRate();
        return $query->available()->where(function ($query) use($minValue,$minRate) {
            $query->where('discount_value', '>=', $minValue)->orWhere('discount_rate', '>=', $minRate);
        });
    }

}
