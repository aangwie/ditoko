<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display list of orders for admin.
     */
    public function index()
    {
        $orders = Order::with('user', 'items.product')
            ->latest()
            ->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Show order detail for admin.
     */
    public function show(Order $order)
    {
        $order->load('user', 'items.product');
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Verify manual payment.
     */
    public function cancel(Order $order)
    {
        $order->update(['payment_status' => 'failed']);
        return redirect()->route('admin.orders.index')
            ->with('success', 'Pesanan ' . $order->order_number . ' dibatalkan.');
    }

    public function verify(Order $order)
    {
        $order->update(['payment_status' => 'success']);
        
        // Send WhatsApp notification
        if ($order->user->phone) {
            $whatsapp = new WhatsAppService();
            $whatsapp->sendPaymentSuccess($order->user->phone, [
                'order_number' => $order->order_number,
                'total_price' => $order->total_price,
                'id' => $order->id,
            ]);
        }
        
        return redirect()->route('admin.orders.index')
            ->with('success', 'Pembayaran pesanan ' . $order->order_number . ' berhasil diverifikasi.');
    }
}
