<?php

use App\Models\Application;
use App\Models\City;
use App\Models\ServiceProvider;
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
        Schema::create('exam_polygraphs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Application::class)->unique()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->dateTime('date_time');
            $table->foreignIdFor(City::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(ServiceProvider::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('address', 75)->comment('Dirección');
            $table->string('neighborhood', 50)->comment('Barrio');
            $table->enum('result', ['Pendiente', 'Apto', 'No apto'])->default('Pendiente');
            $table->text('description')->nullable()->comment('Descripción');
            $table->text('indication')->nullable()->comment('Indicaciones');
            $table->enum('status', ['Programado', 'Reprogramado', 'Desistió', 'Realizado'])->default('Programado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_polygraphs');
    }
};
