<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\RewardPoint;
use Illuminate\Http\Request;

class RewardPointController extends Controller
{
    public function index()
    {
        $rewardPoint = RewardPoint::firstOrCreate(
            ['user_id' => auth()->id()],
            ['balance' => 0, 'total_earned' => 0, 'total_redeemed' => 0]
        );

        $transactions = $rewardPoint->transactions()->latest()->paginate(20);

        return view('customer.reward-points.index', compact('rewardPoint', 'transactions'));
    }
}
