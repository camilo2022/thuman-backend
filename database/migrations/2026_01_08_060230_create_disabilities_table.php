<?php

use App\Models\CIECode;
use App\Models\DisabilityType;
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
        Schema::create('disabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Person::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(DisabilityType::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(CIECode::class, 'cie_code_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
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
        Schema::dropIfExists('disabilities');
    }
};
