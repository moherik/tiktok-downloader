<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        \App\Models\User::create(['username' => 'admin', 'password' => Hash::make('password')]);
        \App\Models\AdsCode::create([
            'placement' => 'below-search',
            'type' => 'ADSENSE' 
        ]);
        \App\Models\AdsCode::create([
            'placement' => 'above-step',
            'type' => 'ADSENSE' 
        ]);
        \App\Models\AdsCode::create([
            'placement' => 'below-step',
            'type' => 'ADSENSE' 
        ]);
    }
}
