<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionLimit
{
    public function handle(Request $request, Closure $next): Response
    {
        $vendor = auth()->user()->vendor;

        if (!$vendor) {
            abort(403);
        }

        $subscription = $vendor->subscription;
        $plan = $subscription?->plan;
        $productLimit = $plan?->product_limit ?? config('shopsphere.subscription_plans.free_product_limit', 10);

        if ($vendor->products()->count() >= $productLimit) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => "Product limit reached ({$productLimit}). Please upgrade your plan.",
                    'product_limit' => $productLimit,
                    'current_count' => $vendor->products()->count(),
                ], 403);
            }

            return redirect()->route('vendor.subscription.plans')
                ->with('error', "You have reached your product limit ({$productLimit}). Please upgrade your plan.");
        }

        return $next($request);
    }
}
