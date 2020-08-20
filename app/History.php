<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $fillable = [
        'video_id', 'user_id'
    ];

    /**
     * Database Relations
     */

    // History belongs to video
    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    // History belongs to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
