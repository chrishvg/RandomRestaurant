<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\Recipe;
use App\Models\History;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function index()
    {
        $ingredients = $this->getKitchenRequest('/api/ingredients');
        $purchases = $this->getKitchenRequest('/api/ingredients/purchasehistory');
        $recipes = Recipe::all();
        $histories = History::orderBy('created_at', 'desc')->get();
        return view('welcome', compact('recipes','histories', 'ingredients', 'purchases'));
    }

    public function serve_recipe(Request $request)
    {
        $recipeSelected = $this->selectRecipe();
        $recipe = Recipe::find($recipeSelected);
        $enoughIngredients = true;
        $ingredients = json_decode($recipe->ingredients);
        foreach($ingredients as $ingredient => $quantity) {
            if (!$this->enoughIngredient($ingredient, $quantity)) {
                $enoughIngredients = false;
                break;
            }
        }
        if ($enoughIngredients) {
            foreach($ingredients as $ingredient => $quantity) {
                $this->takeIngredient($ingredient, $quantity);
            }
        } else {
            return redirect()->back()->with('error','Without ingredients');
        }
        $history = new History();
        $history->id_recipe = $recipe->id;
        $history->name = $recipe->name;
        $history->ingredients = $recipe->ingredients;
        $history->save();

        return redirect()->back()->with('message','Served correctly');
    }

    public function selectRecipe()
    {
        $random_number = rand(100, 699);
        return  substr($random_number, 0, 1);
    }

    public function enoughIngredient($nameIngredient, $quantityNeeded)
    {
        $response = Http::post(ENV('URL_KITCHEN') . '/api/ingredients/enough', [
            'name' => $nameIngredient,
            'quantity' => $quantityNeeded
        ]);
        if ($response->successful()) {
            $ingredients = $response->json();
            if ($ingredients) {
                return true;
            }
        }

        return false;
    }

    public function takeIngredient($nameIngredient, $quantityNeeded): void
    {
        $response = Http::post(ENV('URL_KITCHEN') . '/api/ingredients/take', [
            'name' => $nameIngredient,
            'quantity' => $quantityNeeded
        ]);
    }

    private function getKitchenRequest($path) {
        $response = Http::get(ENV('URL_KITCHEN') . $path);
        if ($response->successful()) {
            return $response->json()['data'];
        }

        return [];
    }
}
