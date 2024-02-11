<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pokemon extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $casts = [
        'abilities' => 'array'
    ];

    protected $guarded = ['id'];

    protected $table = 'pokemons';

    /**
     * @param string|null $region
     * @param string|null $location
     * @return array
     */
    public static function getAllPokemons(?string $region = '',?string $location = '') : array
    {
        $pokemons_query = self::select([
                'pokemons.id',
                'pokemons.name',
                'pokemons.location',
                'pokemons.image',
                'pokemons.shape',
                'pokemons.abilities',
            ])
            ->leftJoin('locations', 'locations.name', '=', 'pokemons.location')
            ->leftJoin('regions', 'locations.region_id', '=', 'regions.id');

        if ($region !== '') {
            $pokemons_query->where('regions.name', '=', $region);
        }

        if ($location !== '') {
            $pokemons_query->where('locations.name', '=', $location);
        }

        $pokemons = $pokemons_query
            ->groupBy('pokemons.id')
            ->orderBy('pokemons.location')
            ->get()
            ->toArray();

        return $pokemons;
    }
}
