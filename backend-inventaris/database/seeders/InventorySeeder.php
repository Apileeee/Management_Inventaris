<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory;
use App\Models\Member;
use Illuminate\Support\Str;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        $members = Member::all();

        Inventory::insert([
            [
                'inventaris_id' => 'INV-' . str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT),
                'item_name' => 'Laptop Lenovo Thinkpad',
                'type' => 'Laptop',
                'serial_number' => 'SN-LEN-001',
                'specification' => 'RAM 16GB, SSD 512GB',
                'status' => 'Baik',
                'assigned_to' => $members[0]->id,
                'department' => 'Technology',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'inventaris_id' => 'INV-' . str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT),
                'item_name' => 'Printer HP LaserJet',
                'type' => 'Printer',
                'serial_number' => 'SN-HP-002',
                'specification' => 'Laser Monochrome',
                'status' => 'Rusak',
                'assigned_to' => $members[1]->id,
                'department' => 'Marketing',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'inventaris_id' => 'INV-' . str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT),
                'item_name' => 'Proyektor Epson',
                'type' => 'Proyektor',
                'serial_number' => 'SN-EPS-003',
                'specification' => 'Full HD 1080p',
                'status' => 'Baik',
                'assigned_to' => null,
                'department' => 'Finance',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
