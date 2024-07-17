<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsumerCard extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'consumer_cards';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    /**
     * The database table used by the model.
     *
     * @var datetime
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * Get the consumer detail.
     */
    public function consumer()
    {
        return $this->belongsTo(\App\Models\Consumer::class);
    }
}
