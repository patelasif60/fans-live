<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasOwnerRelationShip;
/**
 * Product model class for table request.
 */
class Product extends Model
{
	use HasOwnerRelationShip;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'club_id', 'title', 'short_description', 'description', 'image', 'image_file_name', 'price', 'vat_rate', 'rewards_percentage_override', 'status', 'is_restricted_to_over_age', 'created_by', 'updated_by',
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * Get product Category.
     */
    public function productCategory()
    {
        return $this->hasMany(ProductCategory::class, 'product_id');
    }

    /**
     * Get product option.
     */
    public function productOption()
    {
        return $this->hasMany(ProductOption::class, 'product_id');
    }

    /**
     * Get product collection point.
     */
    public function productCollectionPoint()
    {
        return $this->hasMany(ProductCollectionPoint::class, 'product_id');
    }

    /**
     * The category that belong to the product.
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_category', 'product_id', 'category_id');
    }

    /**
     * The category that belong to the product.
     */
    public function specialOffers()
    {
        return $this->belongsToMany(SpecialOffer::class, 'special_offer_products', 'product_id', 'special_offer_id');
    }

    /**
     * Get vat amount.
     */
    public function getVatAmountAttribute()
    {
        return ($this->price * $this->vat_rate) / 100;
    }

    /**
     * Get final price.
     */
    public function getFinalPriceAttribute()
    {
        return formatNumber($this->price + $this->vatAmount);
    }

    /**
     * Get price.
     */
    public function getPriceAttribute()
    {
        return formatNumber($this->attributes['price']);
    }

    /**
     * Get vat rate.
     */
    public function getVatRateAttribute()
    {
        return formatNumber($this->attributes['vat_rate']);
    }
}
