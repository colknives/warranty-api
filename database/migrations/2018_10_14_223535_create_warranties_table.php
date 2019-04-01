<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarrantiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warranties', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid', 50);
            $table->integer('claim_no');
            $table->string('firstname', 100)->nullable();
            $table->string('lastname', 100)->nullable();
            $table->string('contact_number', 50);
            $table->string('email', 130)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('suburb', 255)->nullable();
            $table->string('postcode',50)->nullable();
            $table->string('country',50)->nullable();
            $table->string('invoice_number',50)->nullable();
            $table->string('serial_number',50)->nullable();
            $table->date('purchase_date')->nullable();
            $table->enum('product_type', [
                'DURA SEAL Paint Protection', 
                'DURA SEAL Fabric Protection', 
                'DURA SEAL Leather Protection',
                'Premium Care Fabric',
                'Premium Care Leather',
                'Premium Care Synthetic',
                'Premium Care Outdoor',
                'Soil Guard',
                'Leather Guard'])->nullable();
            $table->string('product_applied',100)->nullable();
            $table->string('proof_purchase', 100)->nullable();
            $table->string('dealer_name', 50)->nullable();
            $table->string('dealer_location', 50)->nullable();
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
        Schema::dropIfExists('warranties');
    }
}
