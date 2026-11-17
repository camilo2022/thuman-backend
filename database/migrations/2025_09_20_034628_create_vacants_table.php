<?php

use App\Models\ContractType;
use App\Models\Headquarter;
use App\Models\HiringReason;
use App\Models\Person;
use App\Models\Position;
use App\Models\WorkArrangement;
use App\Models\WorkDay;
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
        Schema::create('vacants', function (Blueprint $table) {
            $table->id()->comment('Identificación');
            $table->uuid('uuid')->unique()->comment('Identificador público');
            $table->foreignIdFor(HiringReason::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Position::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Person::class)->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Headquarter::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(ContractType::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(WorkArrangement::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(WorkDay::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->decimal('salary_min', 10, 2)->comment('Salario maximo');
            $table->decimal('salary_max', 10, 2)->nullable()->comment('Salario minimo');
            $table->date('start_date')->nullable()->comment('Fecha inicio');
            $table->date('end_date')->nullable()->comment('Fecha fin');
            $table->text('description')->nullable()->comment('Descripcion');
            $table->unsignedBigInteger('quantity')->default(1);
            $table->enum('status', ['En revisión', 'Aprobado', 'Rechazado', 'Publicado', 'Finalizado'])->default('En revisión');
            $table->json('settings')->default(new Expression('(JSON_OBJECT())'))->comment('Configuraciones');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacants');
    }
};
