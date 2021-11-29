<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_number')->unique()->nullable()->index(); // for 1C

            $table->string('status')->default('pending_payment');

            $table->boolean('sent_to_1c')->default(0);

            $table->integer('amount')->default(0);
            $table->unsignedInteger('quantity');
            $table->integer('weight')->default(0); //Вес в граммах

            $table->boolean('payment_method')->default(0); // 0 online, 1 cash
            $table->string('payment_status')->default('pending');

            $table->string('need_change')->nullable();

            $table->boolean('order_type')->default(0); // 0 delivery, 1 pickup
            $table->string('pickup_store')->nullable();
            $table->date('date')->nullable();
            $table->string('delivery_time')->nullable();
            $table->integer('delivery_cost')->default(0);

            $table->json('contact');
            $table->json('address');
            $table->text('order_comment')->nullable();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users');

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
        Schema::dropIfExists('orders');
    }
}
