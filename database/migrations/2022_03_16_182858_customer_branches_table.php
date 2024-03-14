<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CustomerBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_branches', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('customer_id')->unsigned()->index('customer_branches_customer_id_foreign_idx');
			$table->string('name')->nullable()->index();
			$table->string('contact')->nullable()->index();
			$table->string('billing_street')->nullable();
			$table->string('billing_city')->nullable();
			$table->string('billing_state')->nullable()->index();
			$table->string('billing_zip_code')->nullable();
			$table->integer('billing_country_id')->unsigned()->nullable()->index('customer_branches_billing_country_id_foreign_idx');
			$table->string('shipping_street')->nullable();
			$table->string('shipping_city')->nullable();
			$table->string('shipping_state')->nullable()->index();
			$table->string('shipping_zip_code')->nullable();
			$table->integer('shipping_country_id')->unsigned()->nullable()->index('customer_branches_shipping_country_id_foreign_idx');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('customer_branches');
    }
}
