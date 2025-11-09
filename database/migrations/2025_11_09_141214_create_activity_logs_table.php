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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('added_by')->nullable();
            $table->integer('client_id')->nullable();
            $table->string('module')->nullable();
            $table->string('action')->nullable();
            $table->string('table_name')->nullable();
            $table->longText('description')->nullable();
            $table->string('ip')->nullable();
            $table->longText('user_agent')->nullable();
            $table->longText('data_after_action')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
