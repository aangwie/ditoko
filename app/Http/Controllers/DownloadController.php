<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    /**
     * Download a purchased product file securely.
     */
    public function download(Order $order, Product $product)
    {
        // Must be the order owner
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Order must be successful
        if ($order->payment_status !== 'success') {
            return back()->with('error', 'Pembayaran belum lunas. Silakan selesaikan pembayaran terlebih dahulu.');
        }

        // Check if product is in the order
        $orderItem = $order->items()->where('product_id', $product->id)->first();
        if (!$orderItem) {
            abort(404);
        }

        // Check if file exists
        if (!$product->file_path || !Storage::exists($product->file_path)) {
            return back()->with('error', 'File produk tidak ditemukan.');
        }

        return Storage::download($product->file_path);
    }
}
