<?php

use App\Models\Form;
use App\Models\Person;
use App\Models\Vacant;
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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Person::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Vacant::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Form::class)->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->json('questions')->default(new Expression('(JSON_OBJECT())'))->comment('Preguntas');
            $table->json('answers')->default(new Expression('(JSON_OBJECT())'))->comment('Respuestas');
            $table->decimal('salary', 10, 2)->nullable()->comment('Salario');
            $table->text('observation')->nullable()->comment('Observación');
            $table->enum('status', ['Pendiente', 'En revisión', 'Prueba', 'Finalista', 'Contratación', 'Rechazado', 'Desistió', 'Vinculado'])->default('Pendiente');
            $table->unique(['person_id', 'vacant_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
