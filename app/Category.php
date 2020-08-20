<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name'
    ];

    /**
     * Database Relations
     */

    // Category has many videos
    public function videos()
    {
        return $this->hasMany(Video::class);
    }
}
