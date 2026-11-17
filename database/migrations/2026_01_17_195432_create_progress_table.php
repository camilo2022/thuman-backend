<?php

use App\Models\Person;
use App\Models\Step;
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
        Schema::create('progress', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Person::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Step::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->json('questions')->default(new Expression('(JSON_OBJECT())'))->comment('Preguntas');
            $table->json('answers')->default(new Expression('(JSON_OBJECT())'))->comment('Respuestas');
            $table->dateTime('start_at');
            $table->dateTime('end_at')->nullable();
            $table->enum('status', ['En curso', 'Finalizado'])->default('En curso');
            $table->enum('result', ['Pendiente', 'Aprobado', 'Rechazado'])->default('Pendiente');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress');
    }
};
