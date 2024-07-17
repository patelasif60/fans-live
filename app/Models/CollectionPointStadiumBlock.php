<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollectionPointStadiumBlock extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'collection_point_stadium_block';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
}
