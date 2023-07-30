<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class League
 * @package App\Models
 */
class League extends Model
{
    use HasFactory;

    /**
     * @return HasMany
     */
    public function matchGames(): HasMany
    {
        return $this->hasMany(MatchGame::class);
    }

    /**
     * @return BelongsToMany
     */
    public function clubs(): BelongsToMany
    {
        return $this->belongsToMany(Club::class, 'match_games');
    }
}
