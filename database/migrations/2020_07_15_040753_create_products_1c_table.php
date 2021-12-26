<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProducts1cTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_1c', function (Blueprint $table) {
            $table->bigIncrements('id')->index();

            $table->uuid('uuid')->unique()->nullable();
            $table->string('cod1c')->nullable(); //Код из 1с
            $table->string('name');
            $table->string('barcode')->nullable(); //ШтрихкодЕдиницы
            $table->string('vendorcode')->nullable();
            $table->integer('commission')->default(0);
            $table->string('country')->nullable(); // Страна Производства

            $table->integer('price')->default(0);
            $table->integer('stock')->default(0);

            $table->integer('promotion_type')->default(0)->index();
            $table->integer('promotion_price')->nullable();
            $table->string('promotion_percent')->nullable();
            $table->date('promotion_date')->nullable();

            $table->integer('weight')->default(0); //Вес в граммах
            $table->string('size')->nullable();
            $table->string('unit_value')->nullable(); // Значение единицы измерения для вариативности

            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')
                ->references('id')
                ->on('products');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products_1c', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
