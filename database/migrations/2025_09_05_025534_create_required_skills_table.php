<?php

use App\Models\Position;
use App\Models\Skill;
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
        Schema::create('required_skills', function (Blueprint $table) {
            $table->id()->comment('Identificación');
            $table->foreignIdFor(Position::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Skill::class)->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade')->comment('Habilidad');
            $table->unsignedBigInteger('percentage')->comment('Porcentaje de habilidad');
            $table->unique(['position_id', 'skill_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('required_skills');
    }
};
