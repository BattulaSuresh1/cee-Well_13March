<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Customers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name')->index();
			$table->string('first_name', 100)->nullable()->index();
			$table->string('last_name', 100)->nullable()->index();
			$table->string('email')->nullable()->index();
			$table->string('password');
			$table->string('phone', 20)->nullable()->index();
			$table->string('tax_id', 50)->nullable();
			$table->string('customer_type', 16)->nullable();
			$table->string('timezone', 191)->nullable();
			$table->boolean('status')->default(1)->index('customers_is_inactive_index')->comment('1 for active;\n0 for inactive.');
			$table->timestamp('created_at')->nullable()->useCurrent();
			$table->timestamp('updated_at')->nullable();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('customers');
    }
}
