<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVoidStatusToPenjualanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penjualan', function (Blueprint $table) {
            $table->enum('status', ['completed', 'pending_void', 'voided'])->default('completed');
            $table->text('void_reason')->nullable();
            $table->unsignedBigInteger('void_requested_by')->nullable();
            $table->unsignedBigInteger('void_approved_by')->nullable();
            $table->timestamp('void_requested_at')->nullable();
            $table->timestamp('void_approved_at')->nullable();
            
            $table->foreign('void_requested_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('void_approved_by')->references('id')->on('users')->onDelete('set null');
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
            $table->dropForeign(['void_requested_by']);
            $table->dropForeign(['void_approved_by']);
            $table->dropColumn([
                'status', 
                'void_reason', 
                'void_requested_by', 
                'void_approved_by',
                'void_requested_at',
                'void_approved_at'
            ]);
        });
    }
}
