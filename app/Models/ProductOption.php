<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * ProductOption model class for table request.
 */
class ProductOption extends Model
{
     protected $table = 'product_options';

    /**
     * Get product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
