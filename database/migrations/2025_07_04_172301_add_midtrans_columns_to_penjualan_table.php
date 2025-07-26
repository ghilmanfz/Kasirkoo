<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMidtransColumnsToPenjualanTable extends Migration
{
    public function up()
    {
        Schema::table('penjualan', function (Blueprint $table) {
            // kolom boleh nullable supaya transaksi lama tetap aman
           if (!Schema::hasColumn('penjualan', 'order_midtrans')) {
            $table->string('order_midtrans')->nullable()->after('id_penjualan');
      }  });
    }


    public function down()
    {
        Schema::table('penjualan', function (Blueprint $table) {
            $table->dropColumn(['order_midtrans', 'snap_token']);
        });
    }
}
