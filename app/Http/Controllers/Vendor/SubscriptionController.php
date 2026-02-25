<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\VendorSubscription;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function plans()
    {
        $plans = SubscriptionPlan::where('is_active', true)->orderBy('sort_order')->get();
        $vendor = Auth::user()->vendor;
        $currentSubscription = $vendor->subscription;

        return view('vendor.dashboard.plans', compact('plans', 'currentSubscription'));
    }

    public function subscribe(Request $request, SubscriptionPlan $plan)
    {
        $vendor = Auth::user()->vendor;

        if ($plan->isFree()) {
            VendorSubscription::create([
                'vendor_id' => $vendor->id,
                'subscription_plan_id' => $plan->id,
                'starts_at' => now(),
                'ends_at' => null,
                'status' => 'active',
            ]);

            return redirect()->route('vendor.dashboard')->with('success', 'Free plan activated!');
        }

        // For paid plans, redirect to payment
        $paymentService = app(PaymentService::class);

        // Create a temporary order for subscription payment
        return redirect()->route('vendor.subscription.pay', $plan);
    }

    public function confirmSubscription(Request $request, SubscriptionPlan $plan)
    {
        $vendor = Auth::user()->vendor;

        // Expire current subscription
        $vendor->subscriptions()->where('status', 'active')->update(['status' => 'expired']);

        $endsAt = $plan->billing_cycle === 'monthly' ? now()->addMonth() : now()->addYear();

        VendorSubscription::create([
            'vendor_id' => $vendor->id,
            'subscription_plan_id' => $plan->id,
            'starts_at' => now(),
            'ends_at' => $endsAt,
            'status' => 'active',
            'payment_id' => $request->payment_id,
            'payment_gateway' => $request->payment_gateway ?? 'stripe',
        ]);

        // Update vendor commission rate based on plan
        $vendor->update(['commission_rate' => $plan->commission_rate]);

        return redirect()->route('vendor.dashboard')->with('success', 'Subscription activated!');
    }
}
