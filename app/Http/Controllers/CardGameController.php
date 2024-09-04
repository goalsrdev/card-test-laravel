<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CardGameController extends Controller
{
    private $cardValues = [
        '2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6, '7' => 7, '8' => 8, '9' => 9, '10' => 10, 'J' => 11, 'Q' => 12, 'K' => 13, 'A' => 14
    ];

    private $cardSymbols = ['♠', '♥', '♦', '♣'];

    public function index()
    {
        session_start(); 

        if (!session()->has('gameState')) {
            session()->put('gameState', $this->initializeGame());
        }

        $gameState = session('gameState');

        return view('cardgame', compact('gameState'));
    }

    public function handleGuess(Request $request)
    {
        $gameState = session()->get('gameState');
        $action = $request->input('action');

        if ($action === 'guess') {
            $guess = $request->input('guess');
            $currentCardValue = $this->cardValues[$gameState['currentCard']['value']];
            $nextCardValue = $this->cardValues[$gameState['nextCard']['value']];

            $correct = false;
            if (($guess === 'higher' && $nextCardValue > $currentCardValue) ||
                ($guess === 'lower' && $nextCardValue < $currentCardValue)) {
                $correct = true;
            }

            $gameState['lastGuess'] = [
                'current' => $gameState['currentCard'],
                'next' => $gameState['nextCard'],
                'guess' => $guess,
                'correct' => $correct, 
            ];

            if ($correct) {
                $gameState['score']++;
                if ($gameState['score'] === 5) {
                    $gameState['gameOver'] = true;
                    $gameState['message'] = 'Congratulations! You won!';
                    session()->increment('wins');
                } else {
                    $gameState['message'] = 'Correct! Keep going!';
                    $gameState['currentCard'] = $gameState['nextCard'];
                    $gameState['nextCard'] = array_pop($gameState['deck']);
                    $gameState['guessedCards'][] = $gameState['currentCard'];
                }
            } else {
                $gameState['gameOver'] = true;
                $gameState['message'] = 'Sorry, you lost.';
                session()->increment('losses');
            }
        } elseif ($action === 'newGame') {
            $gameState = $this->initializeGame();
        } elseif ($action === 'clearCache') {
            session()->flush(); 
            $gameState = $this->initializeGame();
        }

        session()->put('gameState', $gameState);
        return redirect()->route('cardgame.index'); 
    }

    public function newGame()
    {
        session()->put('gameState', $this->initializeGame());
        return redirect()->route('cardgame.index');
    }

    public function clearSession()
    {
        session()->forget('gameState');
        session()->forget('wins');
        session()->forget('losses');
        return redirect()->route('cardgame.index');
    }

    private function createDeck()
    {
        $deck = [];
        foreach ($this->cardValues as $value => $rank) {
            foreach ($this->cardSymbols as $cardSymbol) {
                $deck[] = ['value' => $value, 'cardSymbol' => $cardSymbol];
            }
        }
        shuffle($deck);
        return $deck;
    }

    private function initializeGame()
    {
        $deck = $this->createDeck();
        $firstCard = array_pop($deck);
        $secondCard = array_pop($deck);
        return [
            'deck' => $deck,
            'currentCard' => $firstCard,
            'nextCard' => $secondCard,
            'guessedCards' => [$firstCard],
            'score' => 0,
            'gameOver' => false,
            'message' => 'Let\'s start',
            'wins' => session('wins', 0),
            'losses' => session('losses', 0),
            'lastGuess' => null,
            'renderCard' => [$this, 'renderCard'] 
        ];
    }

    public function renderCard($card)
    {
        $class = in_array($card['cardSymbol'], ['♥', '♦']) ? 'text-danger' : 'text-warning';
        return "<span class=\"$class\">{$card['value']}{$card['cardSymbol']}</span>";
    }
}