<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\WalletService;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    protected WalletService $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    public function index()
    {
        $vendor = Auth::user()->vendor;

        if (!$vendor) {
            return response()->json(['message' => 'Vendor access only.'], 403);
        }

        $wallet = $this->walletService->getOrCreateWallet($vendor->id);
        $transactions = $wallet->transactions()->latest()->paginate(20);

        return response()->json([
            'wallet' => $wallet,
            'transactions' => $transactions,
        ]);
    }
}
