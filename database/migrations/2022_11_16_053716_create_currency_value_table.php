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
        Schema::create('currency_value', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('currency_type_id')->comment('1 for slide and 2 for non-slide');
            $table->integer('currency_group_id')->unsigned();
            $table->date('period');
            $table->double('value');
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
        Schema::dropIfExists('currency_value');
    }
};
