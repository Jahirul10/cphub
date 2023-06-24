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
        Schema::create('requested_students', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->string('phone');
            $table->string('name');
            $table->string('session');
            $table->string('cfhandle');
            $table->string('vjhandle');
            $table->string('spojhandle');
            $table->timestamps();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('receiver');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requested_students');
    }
};
