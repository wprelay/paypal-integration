<?php

namespace WPRelay\Paypal\Src;

use RelayWp\Affiliate\Core\Payments\RWPPayment;

class Paypal extends RWPPayment
{
    protected $payout = null;

    public static function addPaypalPayment($paymentMethods)
    {
        $paymentMethods['paypal'] = new self();

        return $paymentMethods;
    }

    public function getPaymentSource()
    {
        return [
            'name' => 'Paypal',
            'value' => 'paypal'
        ];
    }

    /**
     * @param $payout
     * @return void
     */
    public function process($payout_ids)
    {
        foreach($payout_ids as $payout_id) {
            true ? $this->paymentSucceeded($payout_id, []) : $this->paymentFailed($payout_id, []);
        }
    }
}