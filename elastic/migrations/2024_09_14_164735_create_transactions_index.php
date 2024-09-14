<?php
declare(strict_types=1);

use Elastic\Adapter\Indices\Mapping;
use Elastic\Adapter\Indices\Settings;
use Elastic\Migrations\Facades\Index;
use Elastic\Migrations\MigrationInterface;

final class CreateTransactionsIndex implements MigrationInterface
{
    /**
     * Run the migration.
     */
    public function up(): void
    {
        Index::create('transactions', function (Mapping $mapping, Settings $settings) {
            $mapping->text('transaction_id');
            $mapping->keyword('merchant_id');
            $mapping->keyword('status');
            $mapping->keyword('operation');
            $mapping->keyword('payment_method');
            $mapping->keyword('reference_no');
            $mapping->keyword('custom_data');
            $mapping->keyword('channel');
            $mapping->keyword('acquirer_id');
            $mapping->keyword('fx_id');
            $mapping->keyword('agent_id');
            $mapping->keyword('customer_id');
            $mapping->date('created_at');
            $mapping->date('updated_at');
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Index::dropIfExists('transactions');
    }
}
