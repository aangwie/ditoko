<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $whatsappSettings = Setting::where('group', 'whatsapp')->get();
        $generalSettings = Setting::where('group', 'general')->get();
        $googleSettings = Setting::where('group', 'google')->get();
        return view('admin.settings.index', compact('whatsappSettings', 'generalSettings', 'googleSettings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'nullable|string',
        ]);

        foreach ($request->settings as $key => $value) {
            Setting::set($key, $value ?? '');
        }

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $request->validate(['logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048']);
            $imageData = base64_encode(file_get_contents($request->file('logo')->path()));
            $mime = $request->file('logo')->getMimeType();
            Setting::set('site_logo', 'data:' . $mime . ';base64,' . $imageData);
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            $request->validate(['favicon' => 'image|mimes:jpeg,png,jpg,gif,svg,ico|max:1024']);
            $imageData = base64_encode(file_get_contents($request->file('favicon')->path()));
            $mime = $request->file('favicon')->getMimeType();
            Setting::set('site_favicon', 'data:' . $mime . ';base64,' . $imageData);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings berhasil diupdate!');
    }

    public function testWhatsApp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
        ]);

        $whatsapp = new \App\Services\WhatsAppService();
        $result = $whatsapp->sendMessage($request->phone, "Test pesan dari DiToko! Konfigurasi WhatsApp Gateway berfungsi baik.");

        return redirect()->route('admin.settings.index')
            ->with('whatsapp_log', $result['log'])
            ->with($result['success'] ? 'success' : 'error', 
                $result['success'] 
                    ? 'Test pesan berhasil dikirim ke ' . $request->phone 
                    : 'Gagal mengirim test pesan. Periksa konfigurasi.');
    }
}
