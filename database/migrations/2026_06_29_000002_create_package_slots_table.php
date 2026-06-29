<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('package_slots', function (Blueprint $table) {
            $table->id();
            $table->string('package_key', 40)->unique();
            $table->unsignedInteger('remaining_slots')->default(250);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_slots');
    }
};
