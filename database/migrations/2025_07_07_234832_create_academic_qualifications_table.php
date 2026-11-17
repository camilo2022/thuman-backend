<?php

use App\Models\AcademicLevel;
use App\Models\City;
use App\Models\FormationStatus;
use App\Models\Profile;
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
        Schema::create('academic_qualifications', function (Blueprint $table) {
            $table->id()->comment('Identificación');
            $table->foreignIdFor(Profile::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(City::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(AcademicLevel::class)->constrained()->onUpdate('cascade')->onDelete('cascade')->comment('Nivel academico');
            $table->string('academic_institution');
            $table->string('academic_title');
            $table->foreignIdFor(FormationStatus::class)->constrained()->onUpdate('cascade')->onDelete('cascade')->comment('Estado de la formación');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_qualifications');
    }
};
