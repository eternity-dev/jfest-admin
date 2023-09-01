<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Enums\OrganizerRoleEnum;
use App\Models\Organizer;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        collect([
            new Organizer([
                'name' => 'Dwi',
                'username' => 'developer',
                'password' => 'developer-jfest-7-bali',
                'role' => OrganizerRoleEnum::Admin,
            ])
        ])->each(function ($organizer) {
            $organizer->save();
        });
    }
}
