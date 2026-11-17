<?php

use App\Models\City;
use App\Models\Country;
use App\Models\Profile;
use App\Models\WorkArrangement;
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
        Schema::create('professional_experiences', function (Blueprint $table) {
            $table->id()->comment('Identificación');
            $table->foreignIdFor(Profile::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('company_name', 80);
            $table->string('supervisor_name', 80);
            $table->foreignIdFor(Country::class, 'supervisor_dialing_country_id')->constrained()->onUpdate('cascade')->onDelete('cascade')->comment('Pais del numero de telefono');
            $table->string('supervisor_phone_number', 30);
            $table->foreignIdFor(City::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('company_address', 75);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('position', 50);
            $table->foreignIdFor(WorkArrangement::class)->constrained()->onUpdate('cascade')->onDelete('cascade')->comment('Modalidad de trabajo');
            $table->text('position_description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('professional_experiences');
    }
};
