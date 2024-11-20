<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductGroupItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_group_items', function (Blueprint $table) {
            $table->bigIncrements('id'); // Primary key
            $table->unsignedBigInteger('group_id'); // Match product_groups.id
            $table->unsignedInteger('product_id'); // Match products.id
            $table->unique(['group_id', 'product_id']); // Prevent duplicate entries
            $table->timestamps();
        
            // Foreign key constraints
            $table->foreign('group_id')
                ->references('id')->on('product_groups')
                ->onDelete('cascade');
        
            $table->foreign('product_id')
                ->references('id')->on('products')
                ->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_group_items');
    }
}