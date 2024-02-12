<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Pokemon;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ImageController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/v1/images/image/{pokemon}",
     *     operationId="getPokemonImageById",
     *     tags={"Pokemons"},
     *     description="Фото покемона",
     *     @OA\Parameter(
     *         name="pokemon",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pokemon image retrieved successfully",
     *         @OA\MediaType(
     *             mediaType="image/jpeg"
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pokemon not found"
     *     )
     * )
     */
    public function getPokemonImage(Pokemon $pokemon): StreamedResponse
    {
        return Storage::response($pokemon->image);
    }


    /**
     * @OA\Get(
     *     path="/api/v1/images/ability_image/{pokemon}",
     *     operationId="getPokemonAbilityImageById",
     *     tags={"Pokemons"},
     *     description="Фото его способности",
     *     @OA\Parameter(
     *         name="pokemon",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pokemon image retrieved successfully",
     *         @OA\MediaType(
     *             mediaType="image/jpeg"
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pokemon not found"
     *     )
     * )
     */
    public function getPokemonAbilityImage(Pokemon $pokemon): StreamedResponse
    {
        return Storage::response($pokemon->abilities['ability_img']);
    }
}
