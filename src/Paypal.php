<?php

namespace RelayWP\Paypal\Src;

defined('ABSPATH') or exit;

use RelayWp\Affiliate\Core\Models\Affiliate;
use RelayWp\Affiliate\Core\Models\Member;
use RelayWp\Affiliate\Core\Payments\RWPPayment;
use RelayWp\Affiliate\Core\Models\Payout;
use RelayWP\Paypal\App\Helpers\PluginHelper;
use RelayWP\Paypal\App\Services\Settings;
use RelayWP\Paypal\Src\Services\MassPay;

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
            'value' => 'paypal',
            'label' => 'Paypal Payment',
            'description' => 'Process Payouts for your affiliates through Paypal',
            'note' => 'You will need a Business Account with Paypal. You will also need to get approval from Paypal Merchant Support Team to add Payouts API feature to your Paypal Account.',
            'target_url' => PluginHelper::getAdminDashboard(),
        ];
    }

    /**
     * @param $payout
     * @return void
     */
    public function process($payout_ids)
    {
        if (\ActionScheduler::is_initialized()) {
            as_schedule_single_action(strtotime("now"), 'rwpa_wpr_process_paypal_payouts', [$payout_ids]);
        } else {
            error_log('ActionScheduler not initialized so Unable to process Payouts Via Paypal');
        }
    }

    public static function sendPayments($payout_ids)
    {
        $ids = implode("','", $payout_ids);

        $memberTable = Member::getTableName();
        $affiliateTable = Affiliate::getTableName();
        $payoutTable = Payout::getTableName();


        $payouts = Payout::query()
            ->select("{$payoutTable}.*, {$memberTable}.email as affiliate_email, {$affiliateTable}.payment_email as paypal_email")
            ->leftJoin($affiliateTable, "$affiliateTable.id = $payoutTable.affiliate_id")
            ->leftJoin($memberTable, "$memberTable.id = $affiliateTable.member_id")
            ->where("{$payoutTable}.id in ('" . $ids . "')")
            ->get();

        $data = [];

        foreach ($payouts as $payout) {
            if (in_array($payout->id, $payout_ids)) {
                $data[] = [
                    'affiliate_email' => $payout->paypal_email,
                    'commission_amount' => $payout->amount,
                    'currency' => $payout->currency,
                    'affiliate_id' => $payout->affiliate_id,
                    'affiliate_payout_id' => $payout->id,
                ];
            }
        }

        $payment_via = Settings::get('paypal_settings.payment_via');

        if ($payment_via == 'latest') {
            [$status, $message] = PayPalClient::processPayout($data);
        } else if ($payment_via == 'legacy') {
            $status = MassPay::processPayout($data);
        } else {
            $status = false;
        }


        if (empty($status)) {
            foreach ($payouts as $payout) {
                if (in_array($payout->id, $payout_ids)) {
                    if (!isset($message)) {
                        $message = 'Payout Failed';
                    }
                    do_action('rwpa_payment_mark_as_failed', $payout->id, ['message' => $message]);
                }
            }
        }
    }
}

