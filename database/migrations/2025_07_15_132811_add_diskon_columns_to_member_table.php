<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiskonColumnsToMemberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('member', function (Blueprint $table) {
        $table->decimal('diskon', 12, 2)->default(0);
        $table->enum('diskon_type', ['percent', 'nominal'])->default('percent');
    });
}

public function down()
{
    Schema::table('member', function (Blueprint $table) {
        $table->dropColumn(['diskon', 'diskon_type']);
    });
}
}
