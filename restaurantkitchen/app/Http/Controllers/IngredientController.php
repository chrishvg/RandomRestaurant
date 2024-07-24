<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIngredinetRequest;
use App\Http\Requests\UpdateIngredinetRequest;
use App\Http\Resources\IngredientCollection;
use App\Http\Resources\IngredientResource;
use Illuminate\Support\Facades\Http;
use App\Models\Ingredient;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    public function index(Request $request)
    {
        return new IngredientCollection(Ingredient::all());
    }

    public function show(Request $request, $name)
    {
        $ingredient = Ingredient::where('name', $name)->first();

        if ($ingredient) {
            return new IngredientResource($ingredient);
        } else {
            return response()->json(['message' => 'Ingrediente no encontrado'], 404);
        }
    }


    public function store(StoreIngredinetRequest $request)
    {
        $validated = $request->validated();

        $ingredient = Ingredient::create($validated);

        return new IngredientResource($ingredient);
    }

    public function update(UpdateIngredinetRequest $request, Ingredient $ingredient)
    {
        $validated = $request->validated();

        $ingredient->update($validated);

        return new IngredientResource($ingredient);
    }

    public function destroy(Request $request, Ingredient $ingredient)
    {
        $ingredient->delete();

        return response()->noContent();
    }

    public function enough(StoreIngredinetRequest $request) {
        $nameIngredient = $request->name;
        $ingredient = Ingredient::where('name', $nameIngredient)->first();
        if ($ingredient->quantity >= $request->quantity) {
            return true;
        } else {
            if ($this->buyIngredients($nameIngredient)) {
                return true;
            }
        }

        return false;
    }

    public function take(UpdateIngredinetRequest $request)
    {
        $ingredient = Ingredient::where('name', $request->name)->first();
        $ingredient->quantity -= $request->quantity;
        $ingredient->save();

        return new IngredientResource($ingredient);
    }

    public function buyIngredients($nameIngredient)
    {
        $response = Http::post(ENV('URL_BUY'), [
            'name' => $nameIngredient
        ]);

        if ($response->successful()) {
            $this->refreshIngredient($nameIngredient);
            return true;
        }

        return false;
    }

    public function refreshIngredient($nameIngredient): void
    {
        $ingredient = Ingredient::where('name', $request->name)->first();
        $ingredient->quantity = 5;
        $ingredient->save();
    }
}
