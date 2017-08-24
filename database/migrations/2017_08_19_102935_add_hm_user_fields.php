<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Migration auto-generated by Sequel Pro Laravel Export.
 *
 * @see https://github.com/cviebrock/sequel-pro-laravel-export
 */
class AddHmUserFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 30)->unique();
            $table->dateTime('date_register');
            $table->string('explicit_password', 50)->nullable();
            $table->enum('status', ['on', 'off', 'suspended'])->nullable();
            $table->text('came_from')->nullable();
            $table->integer('ref')->nullable();
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
            $table->integer('intgold_account')->nullable();
            $table->integer('evocash_account')->nullable();
            $table->integer('egold_account')->nullable();
            $table->string('stormpay_account', 200)->nullable();
            $table->string('ebullion_account', 200)->nullable();
            $table->string('paypal_account', 200)->nullable();
            $table->string('goldmoney_account', 200)->nullable();
            $table->integer('eeecurrency_account')->nullable();
            $table->integer('pecunix_account')->nullable();
            $table->integer('imps')->nullable();
            $table->tinyInteger('is_test')->nullable();
            $table->tinyInteger('identity')->default('0');
            $table->tinyInteger('bad')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropColumn('username');
            $table->dropColumn('date_register');
            $table->dropColumn('explicit_password');
            $table->dropColumn('status');
            $table->dropColumn('came_from');
            $table->dropColumn('ref');
            $table->dropColumn('deposit_total');
            $table->dropColumn('confirm_string');
            $table->dropColumn('ip_reg');
            $table->dropColumn('last_access_time');
            $table->dropColumn('last_access_ip');
            $table->dropColumn('stat_password');
            $table->dropColumn('auto_withdraw');
            $table->dropColumn('user_auto_pay_earning');
            $table->dropColumn('admin_auto_pay_earning');
            $table->dropColumn('pswd');
            $table->dropColumn('hid');
            $table->dropColumn('question');
            $table->dropColumn('answer');
            $table->dropColumn('l_e_t');
            $table->dropColumn('activation_code');
            $table->dropColumn('bf_counter');
            $table->dropColumn('address');
            $table->dropColumn('city');
            $table->dropColumn('state');
            $table->dropColumn('zip');
            $table->dropColumn('country');
            $table->dropColumn('transaction_code');
            $table->dropColumn('payeer_account');
            $table->dropColumn('perfectmoney_account');
            $table->dropColumn('bitcoin_account');
            $table->dropColumn('intgold_account');
            $table->dropColumn('evocash_account');
            $table->dropColumn('egold_account');
            $table->dropColumn('stormpay_account');
            $table->dropColumn('ebullion_account');
            $table->dropColumn('paypal_account');
            $table->dropColumn('goldmoney_account');
            $table->dropColumn('eeecurrency_account');
            $table->dropColumn('pecunix_account');
            $table->dropColumn('imps');
            $table->dropColumn('is_test');
            $table->dropColumn('identity');
        });
    }
}
