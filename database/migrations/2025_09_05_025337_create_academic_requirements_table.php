<?php

use App\Models\AcademicLevel;
use App\Models\FormationStatus;
use App\Models\Position;
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
        Schema::create('academic_requirements', function (Blueprint $table) {
            $table->id()->comment('Identificación');
            $table->foreignIdFor(Position::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(AcademicLevel::class)->constrained()->onUpdate('cascade')->onDelete('cascade')->comment('Nivel academico');
            $table->string('academic_title');
            $table->foreignIdFor(FormationStatus::class)->constrained()->onUpdate('cascade')->onDelete('cascade')->comment('Estado de la formación');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_requirements');
    }
};
