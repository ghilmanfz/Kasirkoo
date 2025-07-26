<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('pembelian', function (Blueprint $table) {
            // kolom status
            if (!Schema::hasColumn('pembelian', 'status')) {
                $table->enum('status', ['pending', 'sukses'])
                      ->default('pending')
                      ->after('bayar');
            }

            // kolom tgl_datang
            if (!Schema::hasColumn('pembelian', 'tgl_datang')) {
                $table->date('tgl_datang')
                      ->nullable()
                      ->after('status');
            }
        });
    }

    public function down()
    {
        Schema::table('pembelian', function (Blueprint $table) {
            $table->dropColumn(['status', 'tgl_datang']);
        });
    }
};
