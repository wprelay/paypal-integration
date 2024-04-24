<?php

namespace WPRelay\Paypal\Src;

use RelayWp\Affiliate\Core\Models\Affiliate;
use RelayWp\Affiliate\Core\Models\Member;
use RelayWp\Affiliate\Core\Models\Order;
use RelayWp\Affiliate\Core\Payments\RWPPayment;
use RelayWp\Affiliate\Core\Models\Payout;
use RelayWp\Affiliate\Core\Models\Transaction;
use WPRelay\Paypal\App\Helpers\Functions;

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

        $ids = implode("','", $payout_ids);
        $memberTable = Member::getTableName();
        $affiliateTable = Affiliate::getTableName();
        $payoutTable = Payout::getTableName();


        $payouts = Payout::query()
            ->select("{$payoutTable}.*, {$memberTable}.email as affiliate_email")
            ->leftJoin($affiliateTable, "$affiliateTable.id = $payoutTable.affiliate_id")
            ->leftJoin($memberTable, "$memberTable.id = $affiliateTable.member_id")
            ->where("{$payoutTable}.id in ('" . $ids . "')")
            ->get();

        $data = [];

        foreach($payouts as $payout) {
            if(in_array($payout->id, $payout_ids)) {
                $data[] = [
                    'affiliate_email' => $payout->affiliate_email,
                    'commission_amount' => $payout->amount,
                    'currency' => $payout->currency,
                    'affiliate_id' => $payout->affiliate_id,
                    'affiliate_payout_id' => $payout->id,
                ];
            }
        }

        $status = PayPalClient::processPayout($data);

        if(empty($status)) {
            foreach($payouts as $payout) {
                if(in_array($payout->id, $payout_ids)) {
                    Transaction::create([
                            'affiliate_id' => $payout->affiliate_id,
                            'type' => Transaction::CREDIT,
                            'currency' => $payout->currency,
                            'amount' => $payout->amount,
                            'transactionable_id' => $payout->id,
                            'transactionable_type' => 'payout',
                            'system_note' => "Payout Failed #{$payout->id} so Refunded",

                    ]);

                    Payout::update([
                        'revert_reason' => 'Payout Failed via Paypal',
                        'deleted_at' => Functions::currentUTCTime(),
                        'status' => 'failed'
                    ], [
                        'id' => $payout->id
                    ]);
                }
            }

        }
        error_log('processing payout');
    }
}