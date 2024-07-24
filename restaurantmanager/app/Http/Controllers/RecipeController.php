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
        $ingredients = [];
        $response = Http::get(ENV('URL_KITCHEN') . '/api/ingredients');
        if ($response->successful()) {
            $ingredients = $response->json()['data'];
        }

        $recipes = Recipe::all();
        $histories = History::orderBy('created_at', 'desc')->get();
        return view('welcome', compact('recipes','histories', 'ingredients'));
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
        $recipeCounts = DB::table('histories')
        ->select('id_recipe', DB::raw('count(*) as count'))
        ->groupBy('id_recipe')
        ->orderBy('count')
        ->get();

        if ($recipeCounts->count() > 0) {
            $minCount = $recipeCounts->first()->count;
            $recipesWithMinCount = $recipeCounts->where('count', $minCount)->pluck('id_recipe');
            $idRecipe = $recipesWithMinCount->first();
            return $idRecipe;
        }

        return rand(1, 6);
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
}
