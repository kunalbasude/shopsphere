<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        $query = Wallet::with('user');

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $wallets = $query->latest()->paginate(20);
        return view('admin.wallets.index', compact('wallets'));
    }

    public function create()
    {
        $users = User::where('role', 'customer')->get();
        return view('admin.wallets.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:credit,debit',
            'description' => 'required|string|max:255',
        ]);

        $wallet = Wallet::firstOrCreate(
            ['user_id' => $validated['user_id']],
            ['balance' => 0, 'total_earned' => 0, 'total_withdrawn' => 0]
        );

        if ($validated['type'] === 'credit') {
            $wallet->balance += $validated['amount'];
            $wallet->total_earned += $validated['amount'];
        } else {
            if ($wallet->balance < $validated['amount']) {
                return back()->withErrors(['amount' => 'Insufficient wallet balance']);
            }
            $wallet->balance -= $validated['amount'];
            $wallet->total_withdrawn += $validated['amount'];
        }

        $wallet->save();

        WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'type' => $validated['type'],
            'amount' => $validated['amount'],
            'balance_after' => $wallet->balance,
            'description' => $validated['description'],
        ]);

        return redirect()->route('admin.wallets.index')
            ->with('success', 'Wallet balance updated successfully');
    }

    public function show($userId)
    {
        $user = User::findOrFail($userId);
        $wallet = Wallet::where('user_id', $userId)->first();
        $transactions = $wallet ? $wallet->transactions()->latest()->paginate(20) : collect();
        
        return view('admin.wallets.show', compact('user', 'wallet', 'transactions'));
    }
}
