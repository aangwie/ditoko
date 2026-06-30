<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public function sendMessage(string $to, string $message): array
    {
        $result = ['success' => false, 'log' => []];

        $log = function(string $msg) use (&$result) {
            Log::info($msg);
            $result['log'][] = $msg;
        };

        if (!Setting::get('whatsapp_enabled', false)) {
            $log('WhatsApp disabled');
            return $result;
        }

        $apiUrl = Setting::get('whatsapp_api_url');
        $apiKey = Setting::get('whatsapp_api_key');
        $sender = Setting::get('whatsapp_sender_number');

        if (!$apiKey || !$apiUrl || !$sender) {
            $log("Missing config — api_key: " . ($apiKey ? 'ok' : 'missing') . ", api_url: " . ($apiUrl ? 'ok' : 'missing') . ", sender: " . ($sender ? 'ok' : 'missing'));
            return $result;
        }

        $phone = $this->formatPhoneNumber($to);
        $log("Recipient formatted: {$phone}");

        try {
            $response = Http::asForm()->post($apiUrl, [
                'nomor_pengirim' => $sender,
                'api_key' => $apiKey,
                'nomor_penerima' => $phone,
                'pesan' => $message,
            ]);

            $log("HTTP status: {$response->status()}");
            $log("Response body: {$response->body()}");

            if ($response->successful()) {
                $log("WhatsApp sent to: {$phone}");
                $result['success'] = true;
                return $result;
            }

            $log('WhatsApp API returned error');
            return $result;
        } catch (\Exception $e) {
            $log('Exception: ' . $e->getMessage());
            return $result;
        }
    }

    public function sendOrderConfirmation(string $to, array $data): bool
    {
        $msg = "Halo! Terima kasih di DiToko.\n\n";
        $msg .= "ORDER #{$data['order_number']}\n";
        $msg .= "Total: Rp ".number_format($data['total_price'],0,',','.')."\n";
        $msg .= "Metode: {$data['payment_method']}\n\n";
        if ($data['payment_method'] === 'manual_transfer') {
            $msg .= "Transfer ke:\nBank BCA\nNo. Rek: 1234567890\na.n: DiToko\n";
            $msg .= "Upload bukti: ".route('orders.show', $data['id']);
        }
        $result = $this->sendMessage($to, $msg);
        return $result['success'];
    }

    public function sendPaymentSuccess(string $to, array $data): bool
    {
        $msg = "Pembayaran berhasil!\n\n";
        $msg .= "Order #{$data['order_number']}\n";
        $msg .= "Akses: ".route('orders.show', $data['id'])."\n\nTerima kasih!";
        $result = $this->sendMessage($to, $msg);
        return $result['success'];
    }

    private function formatPhoneNumber(string $p): string
    {
        $p = preg_replace('/[^0-9]/', '', $p);
        if (str_starts_with($p, '0')) $p = '62'.substr($p,1);
        elseif (!str_starts_with($p, '62')) $p = '62'.$p;
        return $p;
    }
}
