<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartLensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_lenses', function (Blueprint $table) {
            $table->id();
            $table->integer('cart_id')->unsigned();
            $table->string('life_style')->nullable();
            $table->string('lens_recommended')->nullable();
            $table->string('tint_type')->nullable();
            $table->string('tint_value')->nullable();
            $table->string('mirror_coating')->nullable();
            $table->timestamps();
            $table->boolean('status')->default(1)->comment('1 for active;\n0 for inactive.');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_lenses');
    }
}
