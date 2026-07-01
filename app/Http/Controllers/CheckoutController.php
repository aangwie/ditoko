<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\MidtransService;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    /**
     * Show checkout page.
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong.');
        }

        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        $midtransClientKey = null;

        if (config('services.midtrans.server_key')) {
            $midtransService = new MidtransService();
            $midtransClientKey = $midtransService->getClientKey();
        }

        return view('checkout.index', compact('cart', 'total', 'midtransClientKey'));
    }

    /**
     * Process checkout (place order).
     */
    public function store(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:midtrans,bank_transfer,qris',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Keranjang belanja kosong.');
        }

        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        $orderNumber = 'DTK-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => $orderNumber,
                'total_price' => $total,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
            ]);

            foreach ($cart as $id => $item) {
                $order->items()->create([
                    'product_id' => $id,
                    'price' => $item['price'],
                ]);
            }

            DB::commit();
            session()->forget('cart');

            if ($order->user->phone) {
                $whatsapp = new WhatsAppService();
                $whatsapp->sendOrderConfirmation($order->user->phone, [
                    'order_number' => $orderNumber,
                    'total_price' => $total,
                    'payment_method' => $request->payment_method,
                    'id' => $order->id,
                ]);
            }

            if ($request->payment_method === 'midtrans' && config('services.midtrans.server_key')) {
                $midtransService = new MidtransService();
                $snapToken = $midtransService->createSnapToken([
                    'order_number' => $orderNumber,
                    'total_price' => $total,
                    'customer_name' => Auth::user()->name,
                    'customer_email' => Auth::user()->email,
                ]);

                return view('checkout.midtrans-payment', compact('snapToken', 'order'));
            }

            // Manual transfer
            return redirect()->route('orders.show', $order)
                ->with('success', 'Pesanan berhasil dibuat. Silakan lakukan pembayaran transfer.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    /**
     * Handle Midtrans notification callback (webhook).
     */
    public function notification(Request $request)
    {
        Log::info('Midtrans Notification:', $request->all());

        $serverKey = config('services.midtrans.server_key');
        $signatureKey = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($signatureKey !== $request->signature_key) {
            return response('Invalid signature', 403);
        }

        $order = Order::where('order_number', $request->order_id)->first();
        if (!$order) {
            return response('Order not found', 404);
        }

        if ($request->transaction_status === 'settlement' || $request->transaction_status === 'capture') {
            $order->update(['payment_status' => 'success']);
            
            if ($order->user->phone) {
                $whatsapp = new \App\Services\WhatsAppService();
                $whatsapp->sendPaymentSuccess($order->user->phone, [
                    'order_number' => $order->order_number,
                    'total_price' => $order->total_price,
                    'id' => $order->id,
                ]);
            }
        } elseif (in_array($request->transaction_status, ['deny', 'cancel', 'expire'])) {
            $order->update(['payment_status' => 'failed']);
        }

        return response('OK', 200);
    }
}
