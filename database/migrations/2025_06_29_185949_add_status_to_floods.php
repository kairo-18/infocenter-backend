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
        Schema::table('floods', function (Blueprint $table) {
            // boolean status column to indicate if the flood is active or not
            $table->boolean('status')->nullable()->comment('Indicates if the flood is currently active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('floods', function (Blueprint $table) {
            //
        });
    }
};
