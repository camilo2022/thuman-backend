<?php

use App\Models\Person;
use App\Models\SeveranceType;
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
        Schema::create('severances', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Person::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(SeveranceType::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->text('reason');
            $table->enum('status', ['En revisión', 'Aprobado', 'Rechazado'])->default('En revisión');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('severances');
    }
};
