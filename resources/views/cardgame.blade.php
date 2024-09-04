<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guess High-Low</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-display {
            width: 16rem;
            height: 24rem;
        }

        .small-card {
            width: 3rem;
            height: 4rem;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100 bg-light">
    <div class="container">
        <div class="row mt-5 pb-5 bg-white">
            <div class="col-4 mt-4">
                <div class="card card-display mb-2 mx-auto">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        @if (isset($gameState['currentCard']))
                            <div class="display-1">
                                {!! $gameState['renderCard']($gameState['currentCard']) !!}
                            </div>
                        @endif
                    </div>
                    <div class="d-flex mt-4 overflow-auto">
                        @foreach ($gameState['guessedCards'] as $card)
                            <div class="small-card border border-secondary d-flex justify-content-center align-items-center me-2 small">
                                {!! $gameState['renderCard']($card) !!}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-md-8 p-5">
                <h3>Guess Next Card !!</h3>

                <div class="px-8">
                    <div class="mb-4 text-secondary my-4">
                        Wins: {{ $gameState['wins'] }} | Losses: {{ $gameState['losses'] }}
                    </div>
                    <div class="mb-4 text-secondary">Score: {{ $gameState['score'] }}</div>

                    @if ($gameState['message'])
                        <div class="alert alert-info mb-4">
                            {{ $gameState['message'] }}
                        </div>
                    @endif

                    @if (isset($gameState['lastGuess']))
                        <div class="mb-4">
                            @if (isset($gameState['lastGuess']['current']))
                                <span class="h4 me-2">{!! $gameState['renderCard']($gameState['lastGuess']['current']) !!}</span>
                            @endif
                            <span class="mx-2">{{ $gameState['lastGuess']['guess'] ?? '' }}</span>
                            @if (isset($gameState['lastGuess']['next']))
                                <span class="h4 ms-2">{!! $gameState['renderCard']($gameState['lastGuess']['next']) !!}</span>
                            @endif
                            @if(isset($gameState['lastGuess']['correct']))
                                @if($gameState['lastGuess']['correct'])
                                    <span class="badge bg-success ms-2">Correct</span>
                                @else
                                    <span class="badge bg-danger ms-2">Incorrect</span>
                                @endif
                            @endif
                        </div>
                    @endif

                    @if (!$gameState['gameOver'])
                        <form method="POST" action="{{ route('cardgame.guess') }}" class="mb-4">
                            @csrf
                            <input type="hidden" name="action" value="guess"> <div class="btn-group" role="group">
                                <button type="submit" name="guess" value="lower" class="btn btn-outline-primary">
                                    ↓ Lower
                                </button>
                                <button type="submit" name="guess" value="higher" class="btn btn-primary">
                                    ↑ Higher
                                </button>
                            </div>
                        </form>
                    @else
                        <form method="POST" action="{{ route('cardgame.new') }}">
                            @csrf
                            <button type="submit" class="btn btn-success mb-3">
                                New Game
                            </button>
                        </form>
                    @endif

                    <form method="POST" action="{{ route('cardgame.clear') }}">
                        @csrf
                        <button type="submit" class="btn btn-secondary">
                            Clear Session
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>