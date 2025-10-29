<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_registration', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('user_student_id', 45)->nullable();
            $table->string('user_name', 45);
            $table->string('user_email', 45)->unique();
            $table->string('user_password');
            $table->string('user_year_lvl', 45)->nullable();
            $table->string('user_number', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_registration');
    }
};
