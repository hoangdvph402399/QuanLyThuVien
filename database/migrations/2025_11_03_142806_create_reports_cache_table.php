<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsCacheTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports_cache', function (Blueprint $table) {
            $table->id();
            $table->string('kind');
            $table->string('params_hash');
            $table->longText('payload');
            $table->dateTime('generated_at');
            $table->timestamps();
            $table->unique(['kind', 'params_hash']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports_cache');
    }
}
