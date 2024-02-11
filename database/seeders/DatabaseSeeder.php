<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    private array $regions = [
        ['name'=>'Kanto'],
        ['name' => 'Hoenn']
    ];

    private array $locations = [
        ['name' =>'Volcano'],
        ['name' => 'Cinnabar Gym'],
        ['name' => 'Mansion'],
        ['name' => 'Cinnabar Lab']
    ];

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('regions')->upsert($this->regions,['name'],['name']);

        $kanto_id = DB::table('regions')
            ->select('id')
            ->where('name','=','Kanto')
            ->first()
            ->id;

        foreach ($this->locations as $key => $location) {
            $this->locations[$key]['region_id'] = $kanto_id;
        }

        DB::table('locations')->upsert($this->locations,['name'],['name','region_id']);

    }
}
