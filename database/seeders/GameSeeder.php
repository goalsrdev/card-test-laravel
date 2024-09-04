<?php

namespace Database\Seeders;

use App\Models\Game;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class GamesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() 
    {
        // Create a few sample games with dummy data
        Game::factory()->count(10)->createEach(function ($game) {
            $game->score = rand(0, 5);
            $game->won = rand(0, 1) === 1;
            $game->lost = !$game->won;
            $game->deck = $this->createRandomDeck();
            $game->current_card = rand(0, count($game->deck) - 1);
            $game->next_card = rand(0, count($game->deck) - 1);
            $game->guessed_cards = [];
            $game->last_guess = [
                'current' => $game->deck[$game->current_card],
                'next' => $game->deck[$game->next_card],
                'guess' => rand(0, 1) === 1 ? 'higher' : 'lower',
            ];
        });
    }

    private function createRandomDeck()
    {
        $cardValues = [
            "2" => 2,
            "3" => 3,
            "4" => 4,
            "5" => 5,
            "6" => 6,
            "7" => 7,
            "8" => 8,
            "9" => 9,
            "10" => 10,
            "J" => 11,
            "Q" => 12,
            "K" => 13,
            "A" => 14
        ];

        $cardSymbols = ['♠', '♥', '♦', '♣'];

        $deck = [];
        foreach ($cardValues as $value => $rank) {
            foreach ($cardSymbols as $cardSymbol) {
                $deck[] = ['value' => $value, 'cardSymbol' => $cardSymbol];
            }
        }

        shuffle($deck);

        return $deck;
    }
}