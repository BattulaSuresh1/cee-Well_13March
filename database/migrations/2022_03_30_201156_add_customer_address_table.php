<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomerAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {

            $table->string('code')->nullable();
            $table->string('profile_img')->nullable();
            $table->string('profession')->nullable();
            $table->string('alternate_phone')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('age')->nullable();
            $table->string('doa')->nullable();
            $table->string('life_style')->nullable();
            $table->string('address')->nullable();
            $table->string('nearby')->nullable();
			$table->string('city')->nullable()->index();
			$table->string('state')->nullable()->index();
			$table->integer('country_id')->unsigned()->nullable()->index('customer_country_id_foreign_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('code');
            $table->dropColumn('profession');
            $table->dropColumn('alternate_phone');
            $table->dropColumn('date_of_birth');
            $table->dropColumn('age');
            $table->dropColumn('doa');
            $table->dropColumn('life_style');
            $table->dropColumn('address');
            $table->dropColumn('nearby');
			$table->dropColumn('city');
			$table->dropColumn('state');
			$table->dropColumn('country_id');
        });
    }
}
