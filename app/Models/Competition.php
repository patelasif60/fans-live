<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'logo', 'logo_file_name', 'external_app_id', 'status', 'is_primary', 'created_by', 'updated_by',
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'competitions';

    /**
     * The database table used by the model.
     *
     * @var date
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * The clubs that belong to the competition.
     */
    public function clubs()
    {
        return $this->belongsToMany(\App\Models\Club::class, 'competition_club');
    }
}
