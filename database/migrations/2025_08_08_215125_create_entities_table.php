<?php

use App\Models\Country;
use App\Models\DocumentType;
use App\Models\Enterprise;
use App\Models\EntityType;
use App\Models\Person;
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
        Schema::create('entities', function (Blueprint $table) {
            $table->id()->comment('Identificación');
            $table->foreignIdFor(Enterprise::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(EntityType::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('name', 80);
            $table->foreignIdFor(Country::class)->constrained()->onUpdate('cascade')->onDelete('cascade')->comment('Pais');
            $table->foreignIdFor(DocumentType::class)->constrained()->onUpdate('cascade')->onDelete('cascade')->comment('Tipo de documento');
            $table->string('document_number', 30)->unique()->comment('Número de documento');
            $table->foreignIdFor(Person::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->longText('about_us')->nullable()->comment('Sobre nosotros');
            $table->longText('mission')->nullable()->comment('Mision');
            $table->longText('vision')->nullable()->comment('Vision');
            $table->json('settings')->default(new Expression('(JSON_OBJECT())'));
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entities');
    }
};
