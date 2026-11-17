<?php

use App\Models\Bank;
use App\Models\BankAccountType;
use App\Models\Country;
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
        Schema::create('bank_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Person::class)->unique()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Country::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Bank::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(BankAccountType::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('account_number', 80);
            $table->text('observation')->nullable();
            $table->enum('status', ['Pendiente', 'Actualizar', 'Aprobado'])->default('Pendiente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_certificates');
    }
};
