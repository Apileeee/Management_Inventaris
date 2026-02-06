<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('inventaris_id')->unique();
            $table->string('item_name');
            $table->string('type');
            $table->string('serial_number');
            $table->text('specification');
            $table->enum('status', ['Baik','Rusak','Tidak Dipakai','Dilelang']);
            $table->foreignId('assigned_to')
                ->nullable()
                ->constrained('members')
                ->nullOnDelete();
            $table->string('department');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
