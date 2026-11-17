<?php

use App\Models\Country;
use App\Models\Profile;
use App\Models\Relationship;
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
        Schema::create('emergency_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Profile::class)->constrained()->onUpdate('cascade')->onDelete('cascade')->comment('Parentesco');
            $table->string('first_name', 25)->comment('Primer nombre');
            $table->string('second_name', 25)->nullable()->comment('Segundo nombre');
            $table->string('first_last_name', 25)->comment('Primer apellido');
            $table->string('second_last_name', 25)->nullable()->comment('Segundo apellido');
            $table->foreignIdFor(Relationship::class)->constrained()->onUpdate('cascade')->onDelete('cascade')->comment('Parentesco');
            $table->foreignIdFor(Country::class, 'dialing_country_id')->constrained()->onUpdate('cascade')->onDelete('cascade')->comment('Pais del numero de telefono');
            $table->string('phone_number', 30)->unique()->comment('Numero de telefono');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emergency_contacts');
    }
};
