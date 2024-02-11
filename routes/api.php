<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(
    [
        'prefix' => 'v1',
        'as' => '',
        'namespace' => 'App\Http\Controllers\Api\V1',
    ],
    function () {
        Route::group(
            [
                'prefix' => 'pokemons'
            ],
            function () {

                Route::get('/get_all/{region?}/{location?}','PokemonController@getAllPokemons');

                Route::get('/{pokemon}','PokemonController@pokemonShow');

                Route::post('/','PokemonController@pokemonStore');

                Route::delete('/{pokemon}','PokemonController@pokemonDestroy');

                Route::post('/{pokemon}','PokemonController@pokemonUpdate');
            }
        );

        Route::group(
            [
                'prefix' => 'images'
            ],
            function () {
                Route::get('/image/{pokemon}','ImageController@getPokemonImage');

                Route::get('/ability_image/{pokemon}','ImageController@getPokemonAbilityImage');
            }
        );
    }
);
