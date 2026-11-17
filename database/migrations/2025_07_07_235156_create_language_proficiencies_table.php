<?php

use App\Models\Language;
use App\Models\LanguageLevel;
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
        Schema::create('language_proficiencies', function (Blueprint $table) {
            $table->id()->comment('Identificación');
            $table->foreignIdFor(Profile::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Language::class)->constrained()->onUpdate('cascade')->onDelete('cascade')->comment('Idioma');
            $table->foreignIdFor(LanguageLevel::class)->constrained()->onUpdate('cascade')->onDelete('cascade')->comment('Nivel de lenguaje');
            $table->unique(['profile_id', 'language_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('language_proficiencies');
    }
};
