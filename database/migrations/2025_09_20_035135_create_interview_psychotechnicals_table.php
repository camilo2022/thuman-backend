<?php

use App\Models\Application;
use App\Models\Form;
use App\Models\Person;
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
        Schema::create('interview_psychotechnicals', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Application::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->dateTime('date_time');
            $table->foreignIdFor(Form::class)->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->json('questions')->default(new Expression('(JSON_OBJECT())'))->comment('Preguntas');
            $table->json('answers')->default(new Expression('(JSON_OBJECT())'))->comment('Respuestas');
            $table->text('psychologist_observation')->nullable()->comment('Observación del psicólogo');
            $table->unsignedBigInteger('psychologist_score')->nullable()->comment('Calificación del psicólogo (1-5)');
            $table->foreignIdFor(Person::class, 'psychologist_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->enum('psychologist_status', ['Pendiente', 'Aprobado', 'Rechazado'])->default('Pendiente');
            $table->text('indication')->nullable()->comment('Indicaciones');
            $table->enum('status', ['Programado', 'Reprogramado', 'Desistió', 'Realizado'])->default('Programado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interview_psychotechnicals');
    }
};
