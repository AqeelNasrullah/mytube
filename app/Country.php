<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'key', 'name'
    ];

    /**
     * Database Relations
     */

    // Country has many channels
    public function channels()
    {
        return $this->hasMany(Channel::class);
    }
}
