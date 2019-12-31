<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('sale_inv_no');
            $table->integer('user_id');
            $table->integer('customer_id')->default(1);
            $table->integer('totalQty');
            $table->decimal('discount', 15, 3)->default(0);
            $table->decimal('collection', 15, 2)->default(0.00);
            $table->decimal('tax', 15, 3)->nullable();
            $table->bigInteger('subTotal')->nullable();
            $table->bigInteger('grandTotal');
            $table->string('remarks')->nullable();

            // for cash customer
            $table->string('name')->nullable();
            $table->string('mobile')->nullable();
            $table->string('address')->nullable();

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
        Schema::dropIfExists('sales');
    }
}
