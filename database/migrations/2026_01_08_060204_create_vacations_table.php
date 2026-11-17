<?php

use App\Models\Person;
use App\Models\VacationType;
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
        Schema::create('vacations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Person::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(VacationType::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->year('period');
            $table->unsignedBigInteger('days')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', ['En revisión', 'Aprobado', 'Rechazado'])->default('En revisión');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacations');
    }
};
