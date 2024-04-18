<?php

namespace RelayWp\Paypal;

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
    public function process($payout)
    {
        $status = true;
        $this->payout = $payout;



        $status ? $this->paymentSucceeded([]) : $this->paymentFailed([]);

    }
}