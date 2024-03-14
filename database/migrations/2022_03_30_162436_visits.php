<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Visits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id')->unsigned();
			$table->integer('visit_id')->unsigned();
            $table->string('image')->nullable();
            $table->string('visit_date')->nullable();
            $table->string('time_entry')->nullable();
            $table->string('time_exit')->nullable();
            $table->string('time_spent')->nullable();
            $table->string('entry_emotion')->nullable();
            $table->string('exit_emotion')->nullable();
            $table->string('brand_purchased')->nullable();
            $table->string('order_value')->nullable();
            $table->boolean('status')->default(1)->comment('1 for active;\n0 for inactive.');
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
        Schema::dropIfExists('visits');
    }
}
