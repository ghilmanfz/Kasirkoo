<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiskonColumnsToPenjualanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up()
{
    Schema::table('penjualan', function (Blueprint $t) {
        $t->decimal('diskon', 12, 2)->default(0)->change();   // pastikan DECIMAL
        $t->enum('diskon_type', ['percent','nominal'])->default('percent');
    });
}

public function down()
{
    Schema::table('penjualan', function (Blueprint $table) {
        $table->dropColumn('diskon_type');
    });
}

    }
