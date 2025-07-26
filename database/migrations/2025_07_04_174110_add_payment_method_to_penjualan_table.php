<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentMethodToPenjualanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('penjualan', function (Blueprint $table) {
            // ↓ ganti after('status') -> nullable kolom terakhir atau kolom yang ada
            $table->string('payment_method', 20)->nullable()
                  ->after('snap_token');  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('penjualan', function (Blueprint $table) {
            $table->dropColumn('payment_method');
        });
    }
}
