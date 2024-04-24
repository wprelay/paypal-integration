<?php

namespace WPRelay\Paypal\Src\Models;

class BatchPayout extends Model
{
    protected static $table = 'batch_payouts';

    public function createTable()
    {
        $table = static::getTableName();
        $charset = static::getCharSetCollate();

        return "CREATE TABLE {$table} (
                    id BIGINT UNSIGNED AUTO_INCREMENT,
                    sender_batch_id VARCHAR(255),
                    payout_batch_id VARCHAR(255),
                    batch_status VARCHAR(255),
                    email_message text NULL,
                    email_subject text NULL,
                    funding_source VARCHAR(255) NULL,
                    fees json NULL, 
                    created_at TIMESTAMP NOT NULL DEFAULT current_timestamp(),
                    updated_at TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                    deleted_at TIMESTAMP NULL,
                    PRIMARY KEY (id)
                ) {$charset};";
    }
}