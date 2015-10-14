<?php namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Ads extends Model
{

    protected $visible = ['id', 'title', 'start_date', 'end_date', 'minors'];
    protected $fillable = ['title', 'start_date', 'end_date', 'is_whole_system', 'is_promotion',
        'discount_value', 'discount_rate', 'image_display', 'provide_image_link', 'image_url', 'web_url',
        'auto_thumbnail', 'provide_thumbnail_link', 'thumbnail_url'];
    protected $dates = ['start_date', 'end_date'];


    public function items()
    {
        return $this->belongsToMany('App\Item');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Category');
    }

    public function stores()
    {
        return $this->belongsToMany('App\Store');
    }

    public function areas()
    {
        return $this->belongsToMany('App\Area');
    }

    public function scopeAvailable($query)
    {
        return $query->where(function ($query) {
            $query->where('start_date', '<=', Carbon::now())->orWhereNull('start_date');
        })->where('end_date', '>=', new Carbon());
    }

    public function scopePromotion($query)
    {
        return $query->where('is_promotion', true);
    }

    public function scopeForCustomer($query, ActiveCustomer $customer)
    {
        $minValue = $customer->getMinDiscountValue();
        $minRate = $customer->getMinDiscountRate();
        return $query->available()->where(function ($query) use ($minValue, $minRate) {
            $query->where('discount_value', '>=', $minValue)->orWhere('discount_rate', '>=', $minRate);
        });
    }

    public function getImageUrlAttribute($value)
    {
        if (empty($value)) {
            return $value;
        }
        if ($this->provide_image_link) {
            return $value;
        } else {
            return asset($value);
        }
    }

    public function getThumbnailUrlAttribute($value)
    {
        if (empty($value)) {
            return $value;
        }
        if ($this->auto_thumbnail || !$this->provide_thumbnail_link) {
            return asset($value);
        } else {
            return $value;
        }
    }

    public function getTargetsIDAttribute()
    {
        $storesID = $this->stores()->lists('id');
        $areasID = $this->areas()->lists('id');
        return array_merge($storesID, $areasID);
    }

    public function getTargetsAttribute()
    {
        return array_merge($this->areas()->lists('name'), $this->stores()->lists('name'));
    }

    public function getItemsIDAttribute()
    {
        return $this->items()->lists('id');
    }

    public function getStartDateAttribute($date)
    {
        if (!empty($date)) {
            return Carbon::parse($date)->format('Y-m-d');
        } else {
            return Carbon::now()->format('Y-m-d');
        }
    }

    public function getEndDateAttribute($date)
    {
        if (!empty($date)) {
            return Carbon::parse($date)->format('Y-m-d');
        } else {
            return Carbon::now()->addWeek(1)->format('Y-m-d');
        }
    }
}
