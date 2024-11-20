<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductGroup extends Model
{
    protected $guarded = [];


    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_group_items', 'group_id', 'product_id')
                    ->withTimestamps()
                    ->select('products.id', 'products.name'); // Explicitly select columns from products table
    }
    public function cashiers()
    {
        return $this->belongsToMany(User::class, 'cashier_group_assignments', 'group_id', 'cashier_id')
            ->withTimestamps();
    }
}
