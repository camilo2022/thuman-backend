<?php

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
        Schema::create('organizational_structures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entity_id')->constrained('entities')->restrictOnDelete();
            $table->foreignId('position_id')->constrained('positions')->restrictOnDelete();
            $table->foreignId('parent_position_id')->nullable()->constrained('positions')->restrictOnDelete();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->text('reason');
            $table->enum('status', ['Pendiente', 'Aprobado', 'Rechazado', 'Inactivo'])->default('Pendiente');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['entity_id', 'position_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizational_structures');
    }
};
