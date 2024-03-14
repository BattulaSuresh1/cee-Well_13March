<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PrecalValues extends Migration
{
   /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('precal_values', function (Blueprint $table) {
            $table->id();
            $table->integer('cart_id')->unsigned()->nullable()->index('cart_id_foreign_idx');
            $table->string('eye_type')->nullable();
            $table->string('pd')->nullable();
            $table->string('ph')->nullable();
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
        Schema::dropIfExists('precal_values');
    }
}
