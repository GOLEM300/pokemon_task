<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Pokemon;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ImageController extends Controller
{
    public function getPokemonImage(Pokemon $pokemon): StreamedResponse
    {
        return Storage::response($pokemon->image);
    }

    public function getPokemonAbilityImage(Pokemon $pokemon): StreamedResponse
    {
        return Storage::response($pokemon->abilities['ability_img']);
    }
}
