<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashierGroupAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cashier_group_assignments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('cashier_id');
            $table->integer('group_id');
            $table->unique(['cashier_id', 'group_id']); // منع التكرار
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
        Schema::dropIfExists('cashier_group_assignments');
    }
}
