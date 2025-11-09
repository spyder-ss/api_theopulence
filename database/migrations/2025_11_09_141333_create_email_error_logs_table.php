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
        Schema::create('email_error_logs', function (Blueprint $table) {
            $table->id();
            $table->string('email_to')->nullable();
            $table->string('email_from')->nullable();
            $table->string('subject')->nullable();
            $table->longText('message')->nullable();
            $table->longText('error_message')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('is_delete')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_error_logs');
    }
};
