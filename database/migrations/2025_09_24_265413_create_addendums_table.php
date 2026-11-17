<?php

use App\Models\AddendumType;
use App\Models\Contract;
use App\Models\ContractType;
use App\Models\Headquarter;
use App\Models\PaymentPeriod;
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
        Schema::create('addendums', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->comment('Identificador público');
            $table->foreignIdFor(Contract::class)->nullable()->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->foreignIdFor(AddendumType::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Headquarter::class)->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(ContractType::class)->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Position::class)->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->decimal('salary', 15, 2)->nullable()->comment('Salario');
            $table->foreignIdFor(PaymentPeriod::class)->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->date('start_date')->nullable()->comment('Fecha inicio de labores');
            $table->date('end_date')->nullable()->comment('Fecha fin de labores');
            $table->text('person_observation')->nullable()->comment('Observación de la persona');
            $table->dateTime('person_signed_at')->nullable()->comment('Fecha y hora en que firmó la persona');
            $table->enum('person_status', ['Pendiente', 'Actualizar', 'Desistió', 'Firmado'])->default('Pendiente');
            $table->dateTime('legal_representative_signed_at')->nullable()->comment('Fecha y hora en que firmó el representante legal');
            $table->enum('legal_representative_status', ['En revisión', 'Aprobado', 'Rechazado', 'Firmado'])->default('En revisión');
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
        Schema::dropIfExists('addendums');
    }
};
