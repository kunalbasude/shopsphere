<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\RewardPoint;
use App\Models\RewardTransaction;
use Illuminate\Http\Request;

class RewardPointController extends Controller
{
    public function index(Request $request)
    {
        $query = RewardPoint::with('user');

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $rewardPoints = $query->latest()->paginate(20);
        return view('admin.reward-points.index', compact('rewardPoints'));
    }

    public function create()
    {
        $users = User::where('role', 'customer')->get();
        return view('admin.reward-points.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'points' => 'required|integer',
            'type' => 'required|in:credit,debit',
            'description' => 'required|string|max:255',
        ]);

        $rewardPoint = RewardPoint::firstOrCreate(
            ['user_id' => $validated['user_id']],
            ['balance' => 0, 'total_earned' => 0, 'total_redeemed' => 0]
        );

        if ($validated['type'] === 'credit') {
            $rewardPoint->balance += $validated['points'];
            $rewardPoint->total_earned += $validated['points'];
        } else {
            if ($rewardPoint->balance < $validated['points']) {
                return back()->withErrors(['points' => 'Insufficient reward points balance']);
            }
            $rewardPoint->balance -= $validated['points'];
            $rewardPoint->total_redeemed += $validated['points'];
        }

        $rewardPoint->save();

        RewardTransaction::create([
            'user_id' => $validated['user_id'],
            'points' => $validated['points'],
            'type' => $validated['type'],
            'description' => $validated['description'],
            'balance_after' => $rewardPoint->balance,
        ]);

        return redirect()->route('admin.reward-points.index')
            ->with('success', 'Reward points updated successfully');
    }

    public function show($userId)
    {
        $user = User::findOrFail($userId);
        $rewardPoint = RewardPoint::where('user_id', $userId)->first();
        $transactions = RewardTransaction::where('user_id', $userId)->latest()->paginate(20);
        
        return view('admin.reward-points.show', compact('user', 'rewardPoint', 'transactions'));
    }
}
