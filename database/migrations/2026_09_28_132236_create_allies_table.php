<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('allies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entity_id')
                ->constrained('entities');
            $table->foreignId('country_id')
                ->constrained('countries');
            $table->string('name', 80)->comment('Nombre');
            $table->text('description')->nullable()->comment('Descripción');
            $table->json('settings')->default(new Expression('(JSON_OBJECT())'))->comment('Configuraciones');
            $table->timestamps();
            $table->softDeletes();

            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allies');
    }
};
