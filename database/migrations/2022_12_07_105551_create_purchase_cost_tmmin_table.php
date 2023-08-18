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
        Schema::create('purchase_cost_tmmin', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cost_id');
            $table->foreign('cost_id')->references('id')->on('cost_tmmin')->onDelete('cascade');
            $table->string('part_no');
            $table->string('part_name');

            $table->unsignedBigInteger('currency');
            $table->foreign('currency')->references('id')->on('currency_group')->onDelete('cascade');

            $table->integer('type_currency');

            $table->date('period');
            $table->string('value_currency');
            $table->string('cost');
            $table->string('quantity');
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
        Schema::dropIfExists('purchase_cost_tmmin');
    }
};
