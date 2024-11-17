<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stores extends Model
{
    protected $guarded = ['id'];
    protected $appends = ['type_cost_text']; 

    public function scopeHead($query)
    {
        return $query->where('type', 1);
    }
    public function scopeSub($query)
    {
        return $query->where('type', 0);
    }

    public static function forDropdown($business_id, $show_none = false, $filter_use_for_repair = false)
    {
        $business_id = request()->session()->get('user.business_id');
        $store = self::where('business_id',$business_id)->where('type', 1)->pluck('name_ar', 'id')->toArray();
        return $store;
    } 

    public function getTypeCostTextAttribute()
    {
        $cost_type = [
            1 => 'الوارد اولا يخرج اولا',
            2 => 'الوارد اخيرا يخرج اولا',
            3 => 'متوسط التكلفة',
        ];

        return $cost_type[$this->type_cost] ?? 'غير محدد';
    }

    public function parent()
    {
        return $this->belongsTo(Stores::class, 'parent_id');
    }
}
