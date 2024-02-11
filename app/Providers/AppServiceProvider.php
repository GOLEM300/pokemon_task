<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //Кастомное правило на случай если имя покемона занято, но занято им самим при update
        Validator::extend('unique_pokemon_name', function ($attribute, $value, $parameters) {

            $new_pokemon_id = (int)Str::afterLast($parameters[2], '/');

            $old_pokemon = DB::table('pokemons')
                ->select('id')
                ->where('name', '=', $value)
                ->first();

            if (is_null($old_pokemon)) return true;

            $old_pokemon_id = (int)$old_pokemon->id;

            return $new_pokemon_id === $old_pokemon_id;
        });
    }
}
