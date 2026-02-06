<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Inventory extends Model
{
    protected $fillable = [
        'inventaris_id',
        'item_name',
        'type',
        'serial_number',
        'specification',
        'status',
        'assigned_to',
        'department',
    ];

    protected static function booted()
    {
        static::creating(function ($inventory) {
            // Generate inventaris_id: INV-[6 random digits]
            $randomNumber = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
            $inventory->inventaris_id = 'INV-' . $randomNumber;
        });
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'assigned_to');
    }
}
