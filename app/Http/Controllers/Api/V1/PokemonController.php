<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePokemonRequest;
use App\Http\Requests\UpdatePokemonRequest;
use App\Models\Pokemon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PokemonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getAllPokemons(): JsonResponse
    {
        $region = request('region');

        $location = request('location');

        $pokemons = Pokemon::getAllPokemons($region,$location);

        return response()->json(['pokemons' => $pokemons]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function pokemonStore(StorePokemonRequest $request): JsonResponse
    {
        $data = $request->except(['image', 'ability_ru', 'ability_eng', 'ability_img']);

        $image = Storage::putFileAs('pokemons', $request->file('image'), $data['name'] . '.jpg');

        $abilities = [
            'ability_ru' => $request->ability_ru,
            'ability_eng' => $request->ability_eng,
            'ability_img' => Storage::putFileAs('abilities', $request->file('ability_img'), $data['name'] . '.jpg')
        ];

        Pokemon::create($data + ['image' => $image, 'abilities' => $abilities]);

        return response()->json(["message" => 'succeed']);
    }

    /**
     * Display the specified resource.
     */
    public function pokemonShow(Pokemon $pokemon): JsonResponse
    {
        return response()->json(['pokemon' => $pokemon->toArray()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function pokemonUpdate(UpdatePokemonRequest $request, Pokemon $pokemon): JsonResponse
    {
        $data = $request->except(['image', 'ability_ru', 'ability_eng', 'ability_img']);

        //remove old photo
        Storage::delete($pokemon->image);

        Storage::delete($pokemon->abilities['ability_img']);

        $image = Storage::putFileAs('pokemons', $request->file('image'), $data['name'] . '.jpg');

        $abilities = [
            'ability_ru' => $request->ability_ru,
            'ability_eng' => $request->ability_eng,
            'ability_img' => Storage::putFileAs('abilities', $request->file('ability_img'), $data['name'] . '.jpg')
        ];

        $pokemon->update($data + ['image' => $image, 'abilities' => $abilities]);

        return response()->json(["message" => 'succeed']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function pokemonDestroy(Pokemon $pokemon): JsonResponse
    {
        Storage::delete($pokemon->image);

        Storage::delete($pokemon->abilities['ability_img']);

        $pokemon->delete();

        return response()->json(['message' => 'succeed']);
    }

    public function getPokemonImage(Pokemon $pokemon): StreamedResponse
    {
        return Storage::response($pokemon->image);
    }

    public function getPokemonAbilityImage(Pokemon $pokemon): StreamedResponse
    {
        return Storage::response($pokemon->abilities['ability_img']);
    }
}
