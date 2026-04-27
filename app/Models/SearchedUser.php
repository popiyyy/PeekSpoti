<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchedUser extends Model
{
    protected $fillable = [
        'spotify_username',
        'display_name',
        'avatar_url',
        'total_public_playlists',
    ];

    public function cachedAnalysis()
    {
        return $this->hasOne(CachedAnalysis::class);
    }
}
