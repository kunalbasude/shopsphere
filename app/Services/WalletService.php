<?php

namespace App\Services;

use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;

class WalletService
{
    public function getOrCreateWallet(int $vendorId): Wallet
    {
        return Wallet::firstOrCreate(
            ['vendor_id' => $vendorId],
            ['balance' => 0, 'total_earned' => 0, 'total_withdrawn' => 0, 'total_commission_paid' => 0]
        );
    }

    public function creditVendor(int $vendorId, float $amount, int $orderId, float $commissionAmount): WalletTransaction
    {
        return DB::transaction(function () use ($vendorId, $amount, $orderId, $commissionAmount) {
            $wallet = $this->getOrCreateWallet($vendorId);

            $wallet->increment('balance', $amount);
            $wallet->increment('total_earned', $amount);
            $wallet->increment('total_commission_paid', $commissionAmount);

            return WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'order_id' => $orderId,
                'type' => 'credit',
                'amount' => $amount,
                'balance_after' => $wallet->fresh()->balance,
                'description' => "Earning from order #{$orderId} (Commission: \${$commissionAmount})",
                'reference_type' => 'order',
                'reference_id' => $orderId,
            ]);
        });
    }

    public function debit(int $vendorId, float $amount, string $description): WalletTransaction
    {
        return DB::transaction(function () use ($vendorId, $amount, $description) {
            $wallet = $this->getOrCreateWallet($vendorId);

            if ($wallet->balance < $amount) {
                throw new \Exception('Insufficient wallet balance.');
            }

            $wallet->decrement('balance', $amount);
            $wallet->increment('total_withdrawn', $amount);

            return WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'type' => 'debit',
                'amount' => $amount,
                'balance_after' => $wallet->fresh()->balance,
                'description' => $description,
            ]);
        });
    }
}
