<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name'
    ];

    /**
     * Database Relations
     */

    // Role has many users
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
