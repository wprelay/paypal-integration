<?php

namespace WPRelay\Paypal\App\Resources;

class BatchPayoutItemCollection extends Collection
{
    public function toArray($items, $totalCount, $perPage, $currentPage)
    {
        $data = [];
        foreach ($items as $item) {
            $data[] = [
                'receiver_email' => $item->receiver_email,
                'receiver_number' => $item->receiver_number,
                'currency_code' => $item->currency_code,
                'amount' => $item->amount,
                'transaction_status' => $item->transaction_status,
                'payout_item_id' => $item->payout_item_id,
                'payout_batch_id' => $item->payout_batch_id,
                'receipient_wallet' => $item->receipient_wallet,
                'sender_item_id' => $item->sender_item_id,
            ];
        }

        return [
            "total" => $totalCount,
            "per_page" => $perPage,
            "total_pages" => ceil($totalCount / $perPage),
            "current_page" => $currentPage,
            'batch_payout_items' => $data
        ];
    }
}