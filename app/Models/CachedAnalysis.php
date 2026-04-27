<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CachedAnalysis extends Model
{
    protected $fillable = [
        'searched_user_id',
        'top_artists_json',
        'top_genres_json',
        'expires_at',
    ];

    public function searchedUser()
    {
        return $this->belongsTo(SearchedUser::class);
    }
}
