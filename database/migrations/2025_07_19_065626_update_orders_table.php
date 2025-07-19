<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('cancel_by')->nullable();
            $table->date('cancel_date')->nullable();
            $table->boolean('is_refundable')->default(0);
            $table->float('refund_amount')->nullable();
            $table->date('refund_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('cancel_by');
            $table->dropColumn('cancel_date');
            $table->dropColumn('is_refundable');
            $table->dropColumn('refund_amount');
            $table->dropColumn('refund_date');
        });
    }
};
