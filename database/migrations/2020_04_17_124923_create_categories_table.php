<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('menu_name')->nullable();
            $table->string('slug')->index();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->boolean('menu')->default(1);
            $table->boolean('show_in_catalog')->default(0);
            $table->integer('sort')->default(1);

            $table->string('attributes')->nullable();

            $table->unsignedBigInteger('catalog_id')->index();
            $table->foreign('catalog_id')
                ->references('id')
                ->on('catalogs')
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
        Schema::dropIfExists('categories');
    }
}
