<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'score',
        'won',
        'lost',
        'deck',
        'current_card',
        'next_card',
        'guessed_cards',
        'last_guess',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}