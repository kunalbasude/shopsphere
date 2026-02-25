<?php

namespace App\Services;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceService
{
    public function generatePdf(Order $order)
    {
        $order->load('items.product', 'user');

        $pdf = Pdf::loadView('emails.invoice', compact('order'));

        return $pdf;
    }

    public function downloadPdf(Order $order)
    {
        $pdf = $this->generatePdf($order);

        return $pdf->download("invoice-{$order->order_number}.pdf");
    }
}
