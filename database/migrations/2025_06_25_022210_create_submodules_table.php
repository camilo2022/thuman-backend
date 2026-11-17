<?php

use App\Models\Permission;
use App\Models\Role;
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
        Schema::create('submodules', function (Blueprint $table) {
            $table->id();
            $table->morphs('model');
            $table->foreignIdFor(Role::class)->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade')->comment('Rol');
            $table->foreignIdFor(Permission::class)->nullable()->unique()->constrained()->onUpdate('cascade')->onDelete('cascade')->comment('Permiso');
            $table->enum('type', ['Desplegable', 'Vista'])->nullable()->default(null);
            $table->string('name', 80);
            $table->string('route')->nullable();
            $table->text('icon');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submodules');
    }
};
