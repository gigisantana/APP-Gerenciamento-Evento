<?php

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
        Schema::create('atividades', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->date('data');
            $table->time('hora_inicio');
            $table->time('hora_fim');
            $table->unsignedBigInteger('evento_id');
            $table->unsignedBigInteger('categoria_id')->nullable();
            $table->unsignedBigInteger('local_id')->nullable();
            $table->timestamps();
            
            $table->foreign('local_id')->references('id')->on('locais')->onDelete('set null');
            $table->foreign('evento_id')->references('id')->on('eventos')->onDelete('cascade');
            $table->foreign('categoria_id')->references('id')->on('categorias')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atividades');
    }
};
