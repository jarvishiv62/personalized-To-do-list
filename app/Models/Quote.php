<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'content',
        'author',
    ];

    /**
     * Get a random quote from the database.
     *
     * @return Quote|null
     */
    public static function random(): ?Quote
    {
        return self::inRandomOrder()->first();
    }
}