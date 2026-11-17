<?php

use App\Models\Entity;
use App\Models\FormType;
use App\Models\Position;
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
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(FormType::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->nullableMorphs('assignable');
            $table->nullableMorphs('model');
            $table->json('questions')->default(new Expression('(JSON_OBJECT())'))->comment('Preguntas');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forms');
    }
};
