<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('investments', function (Blueprint $table) {
            $table->unsignedSmallInteger('interest_days_credited')->default(0)->after('duration_days');
            $table->timestamp('last_interest_accrued_at')->nullable()->after('interest_days_credited');
        });
    }

    public function down(): void
    {
        Schema::table('investments', function (Blueprint $table) {
            $table->dropColumn(['interest_days_credited', 'last_interest_accrued_at']);
        });
    }
};
