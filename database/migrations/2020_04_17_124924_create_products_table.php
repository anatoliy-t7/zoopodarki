<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id')->index();

            $table->string('name');
            $table->string('slug')->index();
            $table->text('description')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('consist')->nullable(); // Состав
            $table->text('applying')->nullable(); // Применение

            $table->string('country')->nullable(); // Страна Производства

            $table->integer('popularity')->default(0); // for sorting
            $table->integer('price_avg')->default(0); // for sorting

            $table->string('status')->default('active');

            $table->unsignedBigInteger('brand_id')->nullable();
            $table->foreign('brand_id')
                ->references('id')
                ->on('brands');

            $table->unsignedBigInteger('brand_serie_id')->nullable();
            $table->foreign('brand_serie_id')
                ->references('id')
                ->on('brand_series');

            $table->unsignedBigInteger('unit_id')->nullable();
            $table->foreign('unit_id')
                ->references('id')
                ->on('product_units');

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
        Schema::table('products', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
