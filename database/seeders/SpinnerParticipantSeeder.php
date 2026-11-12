<?php

namespace Database\Seeders;

use App\Models\SpinnerParticipant;
use Illuminate\Database\Seeder;

class SpinnerParticipantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultNames = SpinnerParticipant::defaultNames();

        foreach ($defaultNames as $name) {
            SpinnerParticipant::firstOrCreate(
                ['name' => $name],
                ['is_active' => true, 'win_count' => 0]
            );
        }
    }
}

