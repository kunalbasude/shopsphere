<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Services\WalletService;
use Illuminate\Http\Request;
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
        $wallet = $this->walletService->getOrCreateWallet($vendor->id);
        $transactions = $wallet->transactions()->latest()->paginate(20);

        return view('vendor.wallet.index', compact('wallet', 'transactions'));
    }
}
