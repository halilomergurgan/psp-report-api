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
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('transaction_id')->primary();
            $table->unsignedBigInteger('merchant_id');
            $table->enum('status', ['APPROVED', 'WAITING', 'DECLINED', 'ERROR']);
            $table->enum('operation', ['DIRECT', 'REFUND', '3D', '3DAUTH', 'STORED']);
            $table->string('payment_method')->nullable();
            $table->string('reference_no')->nullable();
            $table->string('custom_data')->nullable();
            $table->string('channel')->nullable();
            $table->unsignedBigInteger('acquirer_id')->nullable();
            $table->unsignedBigInteger('fx_id')->nullable();
            $table->unsignedBigInteger('agent_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->timestamps();

            $table->foreign('merchant_id')->references('id')->on('merchants');
            $table->foreign('acquirer_id')->references('id')->on('acquirers');
            $table->foreign('fx_id')->references('id')->on('fx');
            $table->foreign('agent_id')->references('id')->on('agents');
            $table->foreign('customer_id')->references('id')->on('customers');

            $table->index('merchant_id');
            $table->index('acquirer_id');
            $table->index('fx_id');
            $table->index('agent_id');
            $table->index('customer_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
