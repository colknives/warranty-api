<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVehicleInfoFieldInWarranty extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warranties', function (Blueprint $table) {
            $table->string('vehicle_registration', 50)->after('invoice_number');
        });

        Schema::table('warranties', function (Blueprint $table) {
            $table->string('vehicle_make', 50)->nullable()->after('vehicle_registration');
        });

        Schema::table('warranties', function (Blueprint $table) {
            $table->string('vehicle_model', 50)->nullable()->after('vehicle_make');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_tokens', function (Blueprint $table) {
            $table->dropColumn(['vehicle_registration', 'vehicle_make', 'vehicle_model']);
        });
    }
}
