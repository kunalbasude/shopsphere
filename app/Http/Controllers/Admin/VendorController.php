<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $vendors = Vendor::with('user')
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(15);

        return view('admin.vendors.index', compact('vendors'));
    }

    public function show(Vendor $vendor)
    {
        $vendor->load('user', 'products', 'wallet', 'subscription.plan');
        return view('admin.vendors.show', compact('vendor'));
    }

    public function approve(Vendor $vendor)
    {
        $vendor->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Vendor approved successfully.');
    }

    public function reject(Request $request, Vendor $vendor)
    {
        $request->validate(['admin_note' => 'required|string|max:500']);

        $vendor->update([
            'status' => 'rejected',
            'admin_note' => $request->admin_note,
        ]);

        return redirect()->back()->with('success', 'Vendor rejected.');
    }

    public function suspend(Vendor $vendor)
    {
        $vendor->update(['status' => 'suspended']);
        return redirect()->back()->with('success', 'Vendor suspended.');
    }

    public function updateCommission(Request $request, Vendor $vendor)
    {
        $request->validate(['commission_rate' => 'required|numeric|min:0|max:100']);
        $vendor->update(['commission_rate' => $request->commission_rate]);

        return redirect()->back()->with('success', 'Commission rate updated.');
    }
}
