<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Category model class for table request.
 */
class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'club_id', 'title', 'type', 'image', 'image_file_name', 'status', 'rewards_percentage_override', 'is_restricted_to_over_age', 'is_restricted_to_over_age', 'created_by', 'updated_by',
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'categories';
     /**
     * The products that belong to the categories.
     */
    public function categoryProducts()
    {
        return $this->belongsToMany(Product::class, 'product_category', 'category_id','product_id');
    }
}
