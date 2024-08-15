<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIngredinetRequest;
use App\Http\Requests\UpdateIngredinetRequest;
use App\Http\Resources\IngredientCollection;
use App\Http\Resources\PurchaseCollection;
use App\Http\Resources\IngredientResource;
use Illuminate\Support\Facades\Http;
use App\Models\Ingredient;
use App\Models\Purchase;
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
            $quantityNeeded = $request->quantity - $ingredient->quantity;
            if ($this->buyIngredients($nameIngredient, $quantityNeeded)) {
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

    private function buyIngredients($nameIngredient, $quantityNeeded)
    {
        $response = Http::get(ENV('URL_BUY'), [
            'ingredient' => $nameIngredient
        ]);
        if ($response->successful()) {
            $quantitySold = $response->json()['quantitySold'];
            $this->saveHistoryPurchase($nameIngredient, $quantitySold);
            $this->refreshIngredient($nameIngredient, $quantitySold);
            if ($quantitySold > 0 && $quantitySold >= $quantityNeeded) {
                return true;
            }
        }

        return false;
    }

    private function refreshIngredient($nameIngredient, $quantitySold): void
    {
        $ingredient = Ingredient::where('name', $nameIngredient)->first();
        $ingredient->quantity += $quantitySold;
        $ingredient->save();
    }

    private function saveHistoryPurchase($nameIngredient, $quantitySold):void
    {
        $Purchase = new Purchase();
        $Purchase->name = $nameIngredient;
        $Purchase->quantity = $quantitySold;
        $Purchase->save();
    }

    public function getPurchaseHistory(Request $request)
    {
        return new PurchaseCollection(Purchase::orderBy('created_at', 'desc')->get());
    }
}
