<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_item', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->nullable();
            $table->string('barcode')->nullable(); //ШтрихкодЕдиницы
            $table->string('vendorcode')->nullable();
            $table->string('name');
            $table->unsignedInteger('quantity');
            $table->string('unit')->nullable();
            $table->integer('price')->default(0);
            $table->integer('amount')->default(0);
            $table->string('discount_comment')->nullable();
            $table->integer('discount')->default(0);

            $table->unsignedBigInteger('order_id')->index();
            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('cascade');

            $table->unsignedBigInteger('product_id')->index();
            $table->foreign('product_id')
                ->references('id')
                ->on('products_1c');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_item');
    }
}
