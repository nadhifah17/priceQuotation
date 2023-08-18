<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('process_cost_tmmin', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cost_id');
            $table->foreign('cost_id')->references('id')->on('cost_tmmin')->onDelete('cascade');
            $table->string('part_no');
            $table->string('part_name');

            $table->unsignedBigInteger('process_group');
            $table->foreign('process_group')->references('id')->on('process')->onDelete('cascade');

            $table->unsignedBigInteger('process_code');
            $table->foreign('process_code')->references('id')->on('process_code')->onDelete('cascade');

            $table->string('process_rate');
            $table->string('cycle_time');
            $table->string('overhead');
            $table->string('total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('process_cost_tmmin');
    }
};
