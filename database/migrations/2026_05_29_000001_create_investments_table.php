<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('package_key', 40);
            $table->string('package_name', 80);
            $table->decimal('package_price', 12, 2);
            $table->decimal('amount', 12, 2);
            $table->decimal('daily_interest_rate', 5, 3);
            $table->unsignedSmallInteger('duration_days');
            $table->timestamp('starts_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};
