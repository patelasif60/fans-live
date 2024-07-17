<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollectionPoint extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'collection_points';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Get loyalty rewards option.
     */
    public function collectionPointStadiumBlocks()
    {
        return $this->hasMany(CollectionPointStadiumBlock::class, 'collection_point_id');
    }
    /**
     * Get product collection point.
     */
    public function products()
    {
        return $this->belongsToMany(\App\Models\Product::class, 'product_collection_point');
    }
}
