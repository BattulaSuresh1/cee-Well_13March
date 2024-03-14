<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LensPowers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lens_powers', function (Blueprint $table) {
            $table->id();
            $table->integer('prescription_id')->unsigned()->nullable()->index('prescription_id_foreign_idx');
            $table->string('lens_type')->nullable();
            $table->string('eye_type')->nullable();
            $table->string('category')->nullable();
            $table->string('sph')->nullable();
            $table->string('cyl')->nullable();
            $table->string('axis')->nullable();
            $table->string('prism')->nullable();
            $table->string('add')->nullable();
            $table->string('pd')->nullable();
            $table->string('bc')->nullable();
            $table->string('dia')->nullable();
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
        Schema::dropIfExists('lens_powers');
    }
}
