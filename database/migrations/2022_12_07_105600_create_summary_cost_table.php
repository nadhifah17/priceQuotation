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
        Schema::create('summary_cost', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cost_id');
            $table->foreign('cost_id')->references('id')->on('cost')->onDelete('cascade');
            $table->string('part_no');
            $table->string('part_name');
            $table->string('material_cost');
            $table->string('process_cost');
            $table->string('purchase_cost');
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
        Schema::dropIfExists('summary_cost');
    }
};
