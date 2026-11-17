<?php

use App\Models\Departament;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id()->comment('Identificación');
            $table->foreignIdFor(Departament::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('name', 70);
            $table->string('latitude', 20)->nullable();
            $table->string('longitude', 20)->nullable();
            $table->json('settings')->default(new Expression('(JSON_OBJECT())'))->comment('Configuraciones');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
