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
        Schema::create('handles', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->string('cfhandle');
            $table->string('vjhandle');
            $table->string('spojhandle');
            $table->bigInteger('cf_last_submission');
            $table->bigInteger('vj_last_submission');
            $table->bigInteger('spoj_last_submission');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('handles');
    }
};
