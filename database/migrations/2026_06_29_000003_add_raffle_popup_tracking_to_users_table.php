<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedTinyInteger('raffle_popup_shown_count')->default(0)->after('last_ip_address');
            $table->timestamp('raffle_popup_last_shown_at')->nullable()->after('raffle_popup_shown_count');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['raffle_popup_shown_count', 'raffle_popup_last_shown_at']);
        });
    }
};
