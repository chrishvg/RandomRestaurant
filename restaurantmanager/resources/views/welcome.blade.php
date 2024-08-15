<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Random Restaurant</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </head>
    <body>
    <div class="container" style="padding-top:20px">
        @if(session()->has('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif
        @if(session()->has('message'))
            <div class="container">
                <div class="alert alert-success" role="alert">
                    {{ session('message') }}
                </div>
            </div>
        @endif
        <div class="d-flex justify-content-center" style="margin-bottom: 10px">
            <form action="{{ url('/serve_recipe') }}" method="Post">
                @csrf
                <input type="submit" class="btn btn-success" value="Serve Dish">
            </form>
        </div>
        <div class="row">
            <div class="col-sm">
                <p class="font-weight-bold"><strong>Recipes</strong></p>
                <ul class="list-group">
                    @foreach($recipes as $recipe)
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <div>
                                    {{ $recipe->name }}
                                    <ul>
                                        @foreach(json_decode($recipe->ingredients, true) as $ingredient => $quantity)
                                            <li>{{ $ingredient }}: {{ $quantity }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-sm">
                <p class="font-weight-bold"><strong>Current Ingredients</strong></p>
                <ul class="list-group">
                    @foreach($ingredients as $ingredient)
                        <li class="list-group-item">{{ $ingredient['name'] }}: {{ $ingredient['quantity'] }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="col-sm">
                <p class="font-weight-bold"><strong>History</strong></p>
                <ul class="list-group">
                    @forelse($histories as $history)
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <div>
                                <div>Time: {{ $history->created_at->format("h:i A") }}</div>
                                    {{ $history->name }}
                                    <ul>
                                        @foreach(json_decode($history->ingredients, true) as $ingredient => $quantity)
                                            <li>{{ $ingredient }}: {{ $quantity }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item">No records</li>
                    @endforelse
                </ul>
            </div>
            <div class="col-sm">
                <p class="font-weight-bold"><strong>Purchase History</strong></p>
                <ul class="list-group">
                    @forelse($purchases as $purchase)
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <div>
                                <div>Time: {{ date("h:i A", strtotime($purchase['created_at'])) }}</div>
                                    {{ $purchase['name'] }} = {{ $purchase['quantity'] }}
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item">No records</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
    </body>
</html>

