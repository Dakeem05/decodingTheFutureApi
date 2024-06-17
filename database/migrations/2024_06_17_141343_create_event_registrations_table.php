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
        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id();
            $table->uuid()->index();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('otp')->unique()->nullable();
            $table->enum('role', ['student', 'admin'])->default('student');
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->softDeletes()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_registrations');
    }
};
