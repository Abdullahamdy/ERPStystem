<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFolowreNumberFieldIntoResTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('res_tables', function (Blueprint $table) {
            $table->tinyInteger('flower_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('res_tables', function (Blueprint $table) {
            $table->tinyInteger('flower_number')->nullable();
        });
    }
}
