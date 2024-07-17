<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerifyUser extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Define Eloquent Relations.
     *
     * @return [type] [User]
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
