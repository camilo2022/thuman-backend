<?php

use App\Models\City;
use App\Models\Entity;
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
        Schema::create('headquarters', function (Blueprint $table) {
            $table->id()->comment('Identificación');
            $table->foreignIdFor(Entity::class)->constrained()->onUpdate('cascade')->onDelete('cascade')->comment('Entidad');
            $table->string('name', 80)->unique()->comment('Nombre');
            $table->foreignIdFor(City::class)->constrained()->onUpdate('cascade')->onDelete('cascade')->comment('Ciudad');
            $table->string('address', 110)->comment('Direccion');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('headquarters');
    }
};
