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
        Schema::create('test_psychotechnicals', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Application::class)->unique()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->date('start_date')->comment('Fecha inicio');
            $table->date('end_date')->comment('Fecha fin');
            $table->foreignIdFor(Form::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->json('questions')->default(new Expression('(JSON_OBJECT())'))->comment('Preguntas');
            $table->json('answers')->default(new Expression('(JSON_OBJECT())'))->comment('Respuestas');
            $table->text('psychologist_observation')->nullable()->comment('Observación del líder de área');
            $table->foreignIdFor(Person::class, 'psychologist_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->enum('psychologist_status', ['Pendiente', 'Aprobado', 'Rechazado'])->default('Pendiente');
            $table->dateTime('start_at')->nullable()->comment('Fecha y hora de inicio del test');
            $table->dateTime('end_at')->nullable()->comment('Fecha y hora de fin del test');
            $table->text('indication')->nullable()->comment('Indicaciones');
            $table->enum('status', ['Pendiente', 'En curso', 'Finalizado', 'Expirado'])->default('Pendiente')->comment('Estado de la prueba técnica');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_psychotechnicals');
    }
};
