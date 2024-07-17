<?php

namespace App\Models;

use App\Traits\HasOwnerRelationShip;
use Illuminate\Database\Eloquent\Model;

class ClubCategory extends Model
{
    use HasOwnerRelationShip;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'logo', 'logo_file_name', 'status', 'created_by', 'updated_by',
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'club_categories';

    /**
     * The database table used by the model.
     *
     * @var dates
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * Get the clubs for the category.
     */
    public function publishedClubs()
    {
        return $this->hasMany(\App\Models\Club::class, 'club_category_id')->where('status', 'published');
    }
}
