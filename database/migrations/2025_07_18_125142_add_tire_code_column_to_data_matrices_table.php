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
        Schema::table('data_matrices', function (Blueprint $table) {
            if (!Schema::hasColumn('data_matrices', 'tireCode')) {
                $table->string('tireCode')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_matrices', function (Blueprint $table) {
            if (Schema::hasColumn('data_matrices', 'tireCode')) {
                $table->dropColumn('tireCode');
            }
        });
    }
};
