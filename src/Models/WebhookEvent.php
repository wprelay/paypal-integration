<?php

namespace WPRelay\Paypal\Src\Models;

class WebhookEvent extends Model
{
    protected static $table = 'webhooks';

    public function createTable()
    {
        $table = static::getTableName();
        $charset = static::getCharSetCollate();

        return "CREATE TABLE {$table} (
                    id BIGINT UNSIGNED AUTO_INCREMENT,
                    webhook_id VARCHAR(255),
                    create_time VARCHAR(255),
                    resource_type VARCHAR(255),
                    event_type VARCHAR(255),
                    resource_data text,
                    created_at timestamp NOT NULL DEFAULT current_timestamp(),
                    PRIMARY KEY (id)
                   
                ) {$charset};";
    }
}