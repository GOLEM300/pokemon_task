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
     * @OA\Get(
     *     path="/api/v1/pokemons/get_all/",
     *     operationId="getAllPokemons",
     *     tags={"Pokemons"},
     *     description="Найти всех покемонов",
     *     @OA\Parameter(
     *         name="region",
     *         in="query",
     *         description="Регион",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="location",
     *         in="query",
     *         description="Локация",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     )
     * )
     */
    public function getAllPokemons(): JsonResponse
    {
        $region = request('region') ?? '';

        $location = request('location') ?? '';

        $pokemons = Pokemon::getAllPokemons($region,$location);

        return response()->json(['pokemons' => $pokemons]);
    }


    /**
     * @OA\Post(
     *     path="/api/v1/pokemons/",
     *     operationId="createPokemon",
     *     tags={"Pokemons"},
     *     description="Добавить нового покемона",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name", "location", "image", "shape", "ability_ru", "ability_eng", "ability_img"},
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="Имя",
     *                 ),
     *                 @OA\Property(
     *                     property="location",
     *                     type="string",
     *                     description="Локация",
     *                 ),
     *                 @OA\Property(
     *                     property="image",
     *                     type="file",
     *                     description="Фото"
     *                 ),
     *                 @OA\Property(
     *                     property="shape",
     *                     type="string",
     *                     description="Форма",
     *                 ),
     *                 @OA\Property(
     *                     property="ability_ru",
     *                     type="string",
     *                     description="Способность на русском",
     *                 ),
     *                 @OA\Property(
     *                     property="ability_eng",
     *                     type="string",
     *                     description="Способность на английском",
     *                 ),
     *                 @OA\Property(
     *                     property="ability_img",
     *                     type="file",
     *                     description="Фото его способности"
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Pokemon created successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     )
     * )
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
     * @OA\Get(
     *     path="/api/v1/pokemons/{pokemon}",
     *     operationId="getPokemonById",
     *     tags={"Pokemons"},
     *     description="Возвращает покемона по его id",
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
     *         description="Pokemon information retrieved successfully",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pokemon not found"
     *     )
     * )
     */
    public function pokemonShow(Pokemon $pokemon): JsonResponse
    {
        return response()->json(['pokemon' => $pokemon->toArray()]);
    }


    /**
     * @OA\Post(
     *     path="/api/v1/pokemons/{pokemon}",
     *     operationId="updatePokemon",
     *     tags={"Pokemons"},
     *     description="Внести изменения для покемона",
     *     @OA\Parameter(
     *         name="pokemon",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"location", "image", "shape", "ability_ru", "ability_eng", "ability_img"},
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="Имя",
     *                 ),
     *                 @OA\Property(
     *                     property="location",
     *                     type="string",
     *                     description="Локация",
     *                 ),
     *                 @OA\Property(
     *                     property="image",
     *                     type="file",
     *                     description="Фото"
     *                 ),
     *                 @OA\Property(
     *                     property="shape",
     *                     type="string",
     *                     description="Форма",
     *                 ),
     *                 @OA\Property(
     *                     property="ability_ru",
     *                     type="string",
     *                     description="Способность на русском",
     *                 ),
     *                 @OA\Property(
     *                     property="ability_eng",
     *                     type="string",
     *                     description="Способность на английском",
     *                 ),
     *                 @OA\Property(
     *                     property="ability_img",
     *                     type="file",
     *                     description="Фото его способности"
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pokemon updated successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pokemon not found"
     *     )
     * )
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
     * @OA\Delete(
     *     path="/api/v1/pokemons/{pokemon}",
     *     operationId="deletePokemon",
     *     tags={"Pokemons"},
     *     description="Удалить покемона по id",
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
     *         response=204,
     *         description="Pokemon deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pokemon not found"
     *     )
     * )
     */
    public function pokemonDestroy(Pokemon $pokemon): JsonResponse
    {
        Storage::delete($pokemon->image);

        Storage::delete($pokemon->abilities['ability_img']);

        $pokemon->delete();

        return response()->json(['message' => 'succeed']);
    }
}
