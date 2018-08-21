<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_id'); 
            $table->string('product_name')->nullable();
            $table->string('entry_type'); // In or Out 
            $table->string('entry_name'); // (add stock) - In, (sold, showroom, demo, test product) - Out
            $table->string('description')->nullable();
            $table->integer('added_qty');   // 
            $table->integer('current_stock'); 
            $table->integer('user_id');              
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
        Schema::dropIfExists('inventory');
    }
}
