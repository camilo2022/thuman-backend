<?php

use App\Models\PolicyType;
use App\Models\RiskManager;
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
        Schema::create('policies', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->comment('Identificador público');
            $table->morphs('model');
            $table->foreignIdFor(RiskManager::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(PolicyType::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('code', 50);
            $table->decimal('value', 15, 2)->comment('Valor de la póliza');
            $table->decimal('insured_value', 15, 2)->comment('Valor asegurado');
            $table->date('start_date')->comment('Fecha inicio');
            $table->date('end_date')->comment('Fecha fin');
            $table->enum('status', ['En revisión', 'Vigente', 'Vencida', 'Cancelada'])->default('En revisión');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('policies');
    }
};
