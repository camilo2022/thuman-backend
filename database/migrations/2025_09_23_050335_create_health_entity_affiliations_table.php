<?php

use App\Models\Country;
use App\Models\HealthEntity;
use App\Models\Person;
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
        Schema::create('health_entity_affiliations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Person::class)->unique()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Country::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(HealthEntity::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->text('observation')->nullable();
            $table->enum('status', ['Pendiente', 'Actualizar', 'Aprobado'])->default('Pendiente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_entity_affiliations');
    }
};
