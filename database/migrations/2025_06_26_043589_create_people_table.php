<?php

use App\Models\BloodType;
use App\Models\City;
use App\Models\Country;
use App\Models\DocumentType;
use App\Models\Gender;
use App\Models\MaritalStatus;
use App\Models\Sex;
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
        Schema::create('people', function (Blueprint $table) {
            $table->id()->comment('Identificación');
            $table->string('first_name', 25)->comment('Primer nombre');
            $table->string('second_name', 25)->nullable()->comment('Segundo nombre');
            $table->string('first_last_name', 25)->comment('Primer apellido');
            $table->string('second_last_name', 25)->nullable()->comment('Segundo apellido');
            $table->date('birth_date')->nullable()->comment('Fecha de nacimiento');
            $table->foreignIdFor(City::class, 'birth_city_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade')->comment('Ciudad de nacimiento');
            $table->foreignIdFor(DocumentType::class)->constrained()->onUpdate('cascade')->onDelete('cascade')->comment('Tipo de documento');
            $table->foreignIdFor(City::class, 'document_issue_city_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade')->comment('Ciudad de expedicion de documento');
            $table->string('document_number', 30)->unique()->comment('Número de documento');
            $table->date('document_issue_date')->nullable()->comment('Fecha de expedición del documento');
            $table->foreignIdFor(City::class)->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade')->comment('Ciudad de residencia');
            $table->string('address', 75)->nullable()->comment('Dirección');
            $table->string('neighborhood', 50)->nullable()->comment('Barrio');
            $table->foreignIdFor(Country::class, 'dialing_country_id')->constrained()->onUpdate('cascade')->onDelete('cascade')->comment('Pais del numero de telefono');
            $table->string('phone_number', 30)->unique()->comment('Numero de telefono');
            $table->timestamp('phone_number_verified_at')->nullable();
            $table->string('email', 75)->unique()->comment('Correo electrónico');
            $table->timestamp('email_verified_at')->nullable();
            $table->foreignIdFor(Sex::class)->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade')->comment('Sexo');
            $table->foreignIdFor(Gender::class)->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade')->comment('Genero');
            $table->foreignIdFor(BloodType::class)->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade')->comment('Tipo de sangre');
            $table->foreignIdFor(MaritalStatus::class)->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade')->comment('Estado civil');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
