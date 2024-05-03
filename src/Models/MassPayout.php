<?php

namespace WPRelay\Paypal\Src\Models;

class MassPayout extends Model
{
    protected static $table = 'mass_payout_items';

    public function createTable()
    {
        $table = static::getTableName();
        $charset = static::getCharSetCollate();

        return "CREATE TABLE {$table} (
                    id BIGINT UNSIGNED AUTO_INCREMENT,
                    custom_batch_id VARCHAR(255),
                    receiver_email VARCHAR(255),
                    correlation_id VARCHAR(255),
                    payout_id BIGINT UNSIGNED,
                    affiliate_id BIGINT UNSIGNED,
                    ipn_track_id text NULL,
                    payment_gross VARCHAR(255) NULL,
                    mc_currency  VARCHAR(255) null, 
                    mc_gross  VARCHAR(255) null, 
                    masspay_txn_id  VARCHAR(255) null, 
                    unique_id VARCHAR(255), 
                    status VARCHAR(255),
                    payment_fee VARCHAR(255) NULL,
                    payment_date VARCHAR(255),
                    created_at TIMESTAMP NOT NULL DEFAULT current_timestamp(),
                    PRIMARY KEY (id)
                ) {$charset};";
    }
}