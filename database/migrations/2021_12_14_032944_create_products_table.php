<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shop_id');
            $table->string('name');
            $table->string('picture')->nullable();
            $table->nullableMorphs('purchaseable');
            $table->double('price')->default(0);
            $table->string('payment_unit');
            $table->text('conditions')->nullable();
            $table->text('stats')->nullable();
            $table->unsignedInteger('limit')->nullable()->default(1);
            $table->unsignedBigInteger('stock')->nullable();
            $table->timestamp('available_from')->nullable();
            $table->timestamp('available_to')->nullable();
            $table->boolean('is_personal')->default(true);
            $table->boolean('is_publish')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
