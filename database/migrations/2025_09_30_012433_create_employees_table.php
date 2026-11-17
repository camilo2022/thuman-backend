<?php

use App\Models\Headquarter;
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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Person::class)->unique()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Headquarter::class)->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Position::class)->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->date('start_date')->comment('Fecha de ingreso');
            $table->date('end_date')->nullable()->comment('Fecha de retiro');
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
        Schema::dropIfExists('employees');
    }
};
