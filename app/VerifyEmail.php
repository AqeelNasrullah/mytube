<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VerifyEmail extends Model
{
    protected $fillable = [
        'user_id', 'email', 'key', 'expire_at'
    ];

    /**
     * Database Relations
     */

    // Verify email belongs to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
