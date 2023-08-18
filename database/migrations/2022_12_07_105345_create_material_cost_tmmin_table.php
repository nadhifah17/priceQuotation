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
        Schema::create('material_cost_tmmin', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cost_id');
            $table->foreign('cost_id')->references('id')->on('cost_tmmin')->onDelete('cascade');
            $table->string('part_no');
            $table->string('part_name');
            $table->string('currency');
            $table->string('currency_value');

            $table->unsignedBigInteger('material_group');
            $table->foreign('material_group')->references('id')->on('materials')->onDelete('cascade');
            $table->unsignedBigInteger('spec');
            $table->foreign('spec')->references('id')->on('material_specs')->onDelete('cascade');

            $table->date('period');
            $table->string('material_rate');
            $table->string('exchange_rate');
            $table->string('usage_part');
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
        Schema::dropIfExists('material_cost_tmmin');
    }
};
