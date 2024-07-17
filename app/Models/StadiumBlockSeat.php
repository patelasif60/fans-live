<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StadiumBlockSeat extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'stadium_block_seats';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Disable timestamps.
     *
     * @var array
     */
    public $timestamps = false;

    /**
     * The stadium block that belong to the stadium block seat.
     */
    public function stadiumBlock()
    {
        return $this->belongsTo(\App\Models\StadiumBlock::class, 'stadium_block_id');
    }
}
