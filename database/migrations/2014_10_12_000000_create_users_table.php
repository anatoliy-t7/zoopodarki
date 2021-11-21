<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id')->index();

            $table->string('name')->nullable();
            $table->string('phone')->unique()->nullable();

            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();

            $table->string('password');

            $table->boolean('company')->default(0);
            $table->boolean('subscribed')->default(0);

            $table->integer('discount')->default(0);
            $table->uuid('discountGUID')->nullable();
            $table->string('extra_discount')->default('first'); // 'first' = Первая покупка в магазине 200 рублей (дис. карта работает)

            $table->string('review')->default('off');
            $table->date('review_date')->nullable(); // скидка за отзыв 2%, раз в месяц и не новым покупателям

            $table->integer('pref_contact')->default(0);
            $table->integer('pref_address')->default(0);

            $table->rememberToken();

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
        Schema::dropIfExists('users');
    }
}
