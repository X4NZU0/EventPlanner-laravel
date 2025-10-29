<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('event_interaction', function (Blueprint $table) {
    $table->boolean('is_admin')->default(0); // mark if the row is an admin
});

    }

    public function down(): void
    {
        Schema::table('event_interaction', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });
    }
};
