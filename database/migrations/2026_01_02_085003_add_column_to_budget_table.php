<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('budget', function (Blueprint $table) {
            $table->enum('transaction_type', ['income', 'expense'])->default('expense')->after('event_id');
            $table->string('payment_method')->nullable()->after('amount');
            $table->dateTime('payment_date')->nullable()->after('payment_method');
            $table->string('receipt_path')->nullable()->after('status');
            $table->text('notes')->nullable()->after('receipt_path');
            $table->unsignedBigInteger('approved_by')->nullable()->after('notes');
            
            $table->unsignedBigInteger('created_by')->nullable()->after('approved_by');
            
            $table->foreign('approved_by')->references('user_id')->on('users')->onDelete('set null');
            $table->foreign('created_by')->references('user_id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('budget', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['created_by']);
            $table->dropColumn([
                'transaction_type',
                'payment_method',
                'payment_date',
                'receipt_path',
                'notes',
                'approved_by',
                'created_by'
            ]);
        });
    }
};