<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'name',
        'position',
        'departement',
    ];

    public function inventories()
    {
        return $this->hasMany(Inventory::class, 'assigned_to');
    }
}
