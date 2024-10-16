<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('store_number');
            $table->boolean('is_main')->default(false);
            $table->string('name_ar');
            $table->string('name_en');
            $table->string('branch_id')->nullable();
            $table->string('account_number_id')->nullable();
            $table->integer('parent_id')->nullable();
            $table->string('account_name_id')->nullable();
            $table->string('status')->default(0);
            $table->integer('quantity')->nullable();
            $table->tinyInteger('type_cost')->default(0);
            $table->text('description')->nullable();
            $table->integer('store_type')->nullable();
            $table->string('warehouse_id')->nullable();
            $table->tinyInteger('type');
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
        Schema::dropIfExists('stores');
    }
}
