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
        Schema::table('material_specs', function (Blueprint $table) {
            $table->dropColumn('spesification');
            $table->string('specification', 255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('material_specs', function (Blueprint $table) {
            Schema::table('material_specs', function (Blueprint $table) {
                $table->dropColumn('specification');
                $table->string('spesification', 100);
            });
        });
    }
};
