<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartMeasurementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_measurements', function (Blueprint $table) {
            $table->id();
            $table->integer('cart_id')->unsigned();
            $table->string('diameter')->nullable();
            $table->string('base_curve')->nullable();
            $table->string('vertex_distance')->nullable();
            $table->string('pantascopic_angle')->nullable();
            $table->string('frame_wrap_angle')->nullable();
            $table->string('reading_distance')->nullable();
            $table->string('shape')->nullable();

            $table->string('lens_width')->nullable();
            $table->string('bridge_distance')->nullable();
            $table->string('lens_height')->nullable();
            $table->string('temple')->nullable();
            $table->string('total_width')->nullable();
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
        Schema::dropIfExists('cart_measurements');
    }
}
