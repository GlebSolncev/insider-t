<?php

namespace App\Models;

use Database\Factories\PlayerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *
 */
class Player extends Model
{
    use HasFactory;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string[]
     */
    protected $with = ['skills'];

    /**
     * @var string[]
     */
    protected $fillable = [
        'api_id',
        'last_name',
        'first_name',
        'position',
        'rating',
        'club_id',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory()
    {
        return PlayerFactory::new();
    }

    /**
     * @return HasMany
     */
    public function skills(){
        return $this->hasMany(PlayerSkill::class);
    }
}
