<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Migration auto-generated by Sequel Pro Laravel Export
 * @see https://github.com/cviebrock/sequel-pro-laravel-export
 */
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
            $table->increments('id');
            $table->string('name', 200);
            $table->string('username', 30)->unique();
            $table->string('password');
            $table->dateTime('date_register');
            $table->string('email', 200)->unique();
            $table->enum('status', ['on', 'off', 'suspended'])->nullable();
            $table->text('came_from')->nullable();
            $table->string('identity')->default('');
            $table->bigInteger('ref')->nullable();
            $table->float('deposit_total', 10, 2)->default(0.00);
            $table->string('confirm_string', 200)->nullable();
            $table->string('ip_reg', 15)->nullable();
            $table->dateTime('last_access_time')->default('2017-01-01 00:00:00');
            $table->string('last_access_ip', 15)->nullable();
            $table->string('stat_password', 200)->nullable();
            $table->integer('auto_withdraw')->default(1);
            $table->integer('user_auto_pay_earning')->nullable();
            $table->integer('admin_auto_pay_earning')->nullable();
            $table->string('pswd', 50)->nullable();
            $table->string('hid', 50)->nullable();
            $table->string('question', 50)->nullable();
            $table->string('answer', 50)->nullable();
            $table->dateTime('l_e_t')->default('2004-01-01 00:00:00');
            $table->string('activation_code', 50)->nullable();
            $table->unsignedTinyInteger('bf_counter')->nullable();
            $table->string('address', 255)->nullable();
            $table->string('city', 255)->nullable();
            $table->string('state', 255)->nullable();
            $table->string('zip', 255)->nullable();
            $table->string('country', 255)->nullable();
            $table->string('transaction_code', 255)->nullable();
            $table->string('payeer_account', 200)->nullable();
            $table->string('perfectmoney_account', 200)->nullable();
            $table->string('bitcoin_account', 200)->nullable();
            $table->bigInteger('intgold_account')->nullable();
            $table->bigInteger('evocash_account')->nullable();
            $table->bigInteger('egold_account')->nullable();
            $table->string('stormpay_account', 200)->nullable();
            $table->string('ebullion_account', 200)->nullable();
            $table->string('paypal_account', 200)->nullable();
            $table->string('goldmoney_account', 200)->nullable();
            $table->bigInteger('eeecurrency_account')->nullable();
            $table->bigInteger('pecunix_account')->nullable();
            $table->text('ac')->nullable();
            $table->tinyInteger('is_test')->nullable();
            $table->string('explicit_password', 50)->nullable();
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
