<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Products extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name')->nullable()->index();
			$table->string('item_type')->nullable()->index();
			$table->string('item_code')->nullable()->index();
            $table->string('item_description')->nullable()->longText();
            $table->string('images')->nullable()->longText();
            $table->string('available')->nullable();
            $table->string('price')->nullable();
			$table->string('brand')->nullable();
			$table->string('model')->nullable()->index();
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->string('rim_type')->nullable();
            $table->string('collection_type')->nullable();
            $table->string('material')->nullable();
            $table->string('prescription_type')->nullable();
            $table->string('glass_color')->nullable();
            $table->string('frame_width')->nullable();
            $table->string('bar_code')->nullable();
            $table->string('catalog_no')->nullable();
            $table->boolean('status')->default(1)->index('products_is_inactive_index')->comment('1 for active;\n0 for inactive.');
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
        Schema::drop('products');
    }
}
