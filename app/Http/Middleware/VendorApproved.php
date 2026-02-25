<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VendorApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user || !$user->isVendor()) {
            abort(403, 'Vendor access only.');
        }

        $vendor = $user->vendor;

        if (!$vendor || !$vendor->isApproved()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Your vendor account is pending approval.',
                    'status' => $vendor?->status ?? 'not_found',
                ], 403);
            }

            return redirect()->route('vendor.pending');
        }

        return $next($request);
    }
}
