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
        Schema::create('interview_initials', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Application::class)->unique()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->dateTime('date_time');
            $table->foreignIdFor(Form::class)->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->json('questions')->default(new Expression('(JSON_OBJECT())'))->comment('Preguntas');
            $table->json('answers')->default(new Expression('(JSON_OBJECT())'))->comment('Respuestas');
            $table->decimal('salary_min', 10, 2)->nullable()->comment('Salario maximo');
            $table->decimal('salary_max', 10, 2)->nullable()->comment('Salario minimo');
            $table->text('reviewer_observation')->nullable()->comment('Observación del revisor');
            $table->unsignedBigInteger('reviewer_score')->nullable()->comment('Calificación del revisor (1-5)');
            $table->foreignIdFor(Person::class, 'reviewer_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->enum('reviewer_status', ['Pendiente', 'Aprobado', 'Rechazado'])->default('Pendiente');
            $table->text('leader_observation')->nullable()->comment('Observación del líder de área');
            $table->unsignedBigInteger('leader_score')->nullable()->comment('Calificación del líder (1-5)');
            $table->foreignIdFor(Person::class, 'leader_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->enum('leader_status', ['Pendiente', 'Aprobado', 'Rechazado'])->default('Pendiente');
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
        Schema::dropIfExists('interview_initials');
    }
};
