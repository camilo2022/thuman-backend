<?php

use App\Models\Application;
use App\Models\ContractType;
use App\Models\Headquarter;
use App\Models\PaymentPeriod;
use App\Models\Person;
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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->comment('Identificador público');
            $table->foreignIdFor(Application::class)->nullable()->unique()->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->foreignIdFor(Person::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Headquarter::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(ContractType::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Position::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->decimal('salary', 15, 2)->comment('Salario');
            $table->decimal('transport_allowance', 15, 2)->default(0)->comment('Auxilio de transporte');
            $table->foreignIdFor(PaymentPeriod::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->date('start_date')->comment('Fecha inicio de labores');
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
        Schema::dropIfExists('contracts');
    }
};
