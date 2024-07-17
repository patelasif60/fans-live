<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Consumer extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'consumers';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The database table used by the model.
     *
     * @var date
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'receive_offers' => 'boolean',
        'settings'       => 'json',
    ];

    /**
     * Get the user detail.
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Get the club detail.
     */
    public function club()
    {
        return $this->belongsTo(\App\Models\Club::class);
    }

    /**
     * Get all consumer cards.
     */
    public function cards()
    {
        return $this->hasMany(\App\Models\ConsumerCard::class, 'consumer_id', 'id');
    }

    /**
     * Get consumer membership package.
     */
    public function getActiveMembershipPackage()
    {
        return ConsumerMembershipPackage::where('consumer_id', $this->id)->where('club_id', $this->club_id)->where('is_active', 1)->first();
    }

    /**
     * Accessor for Age.
     */
    public function getAgeAttribute()
    {
        return Carbon::parse($this->attributes['date_of_birth'])->age;
    }
}
