<?php

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
        Schema::create('terminations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Person::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->nullableMorphs('model');
            $table->text('reason')->nullable();
            $table->date('date')->nullable();
            $table->enum('decision', ['Terminar', 'Renovar', 'No renovar'])->default('No renovar');
            $table->enum('status', ['En revisión', 'Aprobado', 'Rechazado'])->default('En revisión');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('terminations');
    }
};
