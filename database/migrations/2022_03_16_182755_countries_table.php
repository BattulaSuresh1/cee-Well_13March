<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function(Blueprint $table)
		{
            $table->increments('id');
            $table->string('name', 50)->index('countries_name_index');
            $table->string('code', 5)->index('countries_code_index');
            $table->boolean('status')->default(1)->index('inactive_index')->comment('1 for active;\n0 for inactive.');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('countries');
    }
}
