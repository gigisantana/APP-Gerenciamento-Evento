<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
        public function up()
    {
        Schema::table('atividades', function (Blueprint $table) {
            $table->unsignedBigInteger('categoria_id')->nullable()->after('id');
            $table->unsignedBigInteger('local_id')->nullable()->after('categoria_id');

            $table->foreign('categoria_id')->references('id')->on('categorias')->onDelete('set null');
            $table->foreign('local_id')->references('id')->on('locais')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('atividades', function (Blueprint $table) {
            $table->dropForeign(['categoria_id']);
            $table->dropForeign(['local_id']);
            $table->dropColumn(['categoria_id', 'local_id']);
        });
    }
};
