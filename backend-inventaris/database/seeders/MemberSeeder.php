<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Member;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        Member::insert([
            [
                'name' => 'Andi Saputra',
                'position' => 'Staff IT',
                'departement' => 'Technology',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Budi Santoso',
                'position' => 'Admin',
                'departement' => 'Marketing',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Citra Lestari',
                'position' => 'Manager',
                'departement' => 'Finance',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
