<?php

use App\Models\FileSubtype;
use App\Models\FileType;
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
        Schema::create('files', function (Blueprint $table) {
            $table->id()->comment('Identificación');
            $table->morphs('model');
            $table->string('name');
            $table->foreignIdFor(FileType::class)->constrained()->onUpdate('cascade')->onDelete('cascade')->comment('Tipo de archivo');
            $table->foreignIdFor(FileSubtype::class)->constrained()->onUpdate('cascade')->onDelete('cascade')->comment('Subtipo de archivo');
            $table->string('path');
            $table->string('mime')->nullable();
            $table->string('extension')->nullable();
            $table->float('size')->nullable();
            $table->json('metadata')->default(new Expression('(JSON_OBJECT())'));
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
        Schema::dropIfExists('files');
    }
};
