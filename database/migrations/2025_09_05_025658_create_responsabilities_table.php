<?php

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
        Schema::create('responsabilities', function (Blueprint $table) {
            $table->id()->comment('Identificación');
            $table->foreignIdFor(Position::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('name', 80);
            $table->text('description')->comment('Descripcion');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('responsabilities');
    }
};
