<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class FeedItem extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'feed_items';
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The content feed that belong to the feed item.
     */
    public function contentFeed()
    {
        return $this->belongsTo(\App\Models\ContentFeed::class);
    }
}
