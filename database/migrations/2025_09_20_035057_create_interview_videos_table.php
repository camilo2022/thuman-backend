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
        Schema::create('interview_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Application::class)->unique()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Form::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->json('questions')->default(new Expression('(JSON_OBJECT())'))->comment('Preguntas');
            $table->json('answers')->default(new Expression('(JSON_OBJECT())'))->comment('Respuestas');
            $table->text('reviewer_observation')->nullable()->comment('Observación del revisor');
            $table->foreignIdFor(Person::class, 'reviewer_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->enum('status', ['Aprobado', 'Rechazado'])->default('Aprobado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interview_videos');
    }
};
