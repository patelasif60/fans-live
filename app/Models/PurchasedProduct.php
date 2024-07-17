<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchasedProduct extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'purchased_products';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class, 'product_id');
    }

    /**
     * Get options of a purchased product.
     */
    public function options()
    {
        return $this->belongsToMany(\App\Models\ProductOption::class, 'purchased_product_options', 'purchased_product_id', 'product_option_id');
    }

    /**
     * Get product transactions
     */
    public function productTransaction()
    {
        return $this->belongsTo(\App\Models\ProductTransaction::class, 'product_transaction_id');
    }    
}
