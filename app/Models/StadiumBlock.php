<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * StadiumBlock model class for table request.
 */
class StadiumBlock extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'stadium_blocks';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Get stadium entarnce block detail.
     */
    public function stadiumEntrances()
    {
        return $this->belongsToMany(\App\Models\StadiumEntrance::class);
    }

    /**
     * Get collection points.
     */
    public function collectionPoints()
    {
        return $this->hasMany(CollectionPointStadiumBlock::class, 'stadium_block_id');
    }
}
