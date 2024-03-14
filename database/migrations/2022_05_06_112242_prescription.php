<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Prescription extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id')->unsigned();
            $table->string('lens_type')->nullable();
            $table->string('validity')->nullable();
            $table->string('given_by')->nullable();
            $table->string('attachment')->nullable();

            $table->string('life_style')->nullable();
            $table->string('lens_recommended')->nullable();
            $table->string('tint_type')->nullable();
            $table->string('tint_value')->nullable();
            $table->string('mirror_coating')->nullable();

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
        Schema::dropIfExists('prescriptions');
    }
}