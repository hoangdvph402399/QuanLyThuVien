<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCapacitySnapshotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('capacity_snapshots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('space_id');
            $table->dateTime('hour_bucket');
            $table->integer('capacity');
            $table->integer('reserved')->default(0);
            $table->integer('seated')->default(0);
            $table->timestamps();

            $table->foreign('space_id')->references('id')->on('spaces')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('capacity_snapshots');
    }
}
