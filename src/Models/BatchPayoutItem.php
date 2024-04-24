<?php

namespace WPRelay\Paypal\Src\Models;

class BatchPayoutItem extends Model
{
    protected static $table = 'batch_payouts_items';

    public function createTable()
    {
        $table = static::getTableName();
        $charset = static::getCharSetCollate();

        return "CREATE TABLE {$table} (
                    id BIGINT UNSIGNED AUTO_INCREMENT,
                    batch_id BIGINT UNSIGNED,
                    payout_batch_id VARCHAR(255) NULL,
                    receiver_email VARCHAR(255),
                    receiver_number VARCHAR(255) NULL,
                    currency_code VARCHAR(20),
                    amount VARCHAR(50) NULL,
                    receipient_wallet text NULL,
                    transaction_status VARCHAR(255),
                    payout_item_id VARCHAR(255) NULL,
                    sender_item_id VARCHAR(255),
                    activity_id VARCHAR(255) NULL,
                    payout_item_data json NULL, 
                    time_processed VARCHAR(255) NULL, 
                    affiliate_id BIGINT UNSIGNED, 
                    affiliate_payout_id BIGINT UNSIGNED, 
                    created_at timestamp NOT NULL DEFAULT current_timestamp(),
                    updated_at timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                    deleted_at timestamp NULL,
                    PRIMARY KEY (id)
                ) {$charset};";
    }
}



