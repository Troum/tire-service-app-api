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
        Schema::create('infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_id')->constrained(
                table: 'types', indexName: 'types_type_id'
            );
            $table->foreignId('place_id')->constrained(
                table: 'places', indexName: 'places_place_id'
            );
            $table->string('image_url', 256)->nullable();
            $table->string('name', 256)->nullable();
            $table->integer('amount')->default(0)->nullable();
            $table->decimal('price')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infos');
    }
};
