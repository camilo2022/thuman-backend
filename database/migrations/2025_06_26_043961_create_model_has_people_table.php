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
        Schema::create('model_has_people', function (Blueprint $table) {
            $table->foreignIdFor(Person::class)->constrained()->onUpdate('cascade')->onDelete('cascade')->comment('Persona');
            $table->morphs('model');
            $table->unique(['person_id', 'model_id', 'model_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('model_has_people');
    }
};
