<?php

use App\Models\Area;
use App\Models\ProfileCategory;
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
        Schema::create('positions', function (Blueprint $table) {
            $table->id()->comment('Identificación');
            $table->foreignIdFor(Area::class)->constrained()->onUpdate('cascade')->onDelete('cascade')->comment('Area');
            $table->string('name', 80)->unique()->comment('Nombre');
            $table->foreignIdFor(ProfileCategory::class)->constrained()->onUpdate('cascade')->onDelete('cascade')->comment('Area');
            $table->text('description')->comment('Descripcion');
            $table->json('settings')->default(new Expression('(JSON_OBJECT())'))->comment('Configuraciones');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
