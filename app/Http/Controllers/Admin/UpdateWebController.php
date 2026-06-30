<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class UpdateWebController extends Controller
{
    public function index()
    {
        $token = Setting::get('github_token', '');
        return view('admin.update-web.index', compact('token'));
    }

    public function saveToken(Request $request)
    {
        $request->validate(['github_token' => 'nullable|string']);
        Setting::set('github_token', $request->github_token ?? '');
        return redirect()->route('admin.update-web.index')
            ->with('success', 'Token GitHub berhasil disimpan.');
    }

    public function update(Request $request)
    {
        $token = Setting::get('github_token', '');
        if (!$token) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Token GitHub belum diatur.']);
            }
            return back()->with('error', 'Token GitHub belum diatur. Silakan isi token terlebih dahulu.');
        }

        $output = [];

        $cmd = sprintf(
            'git remote set-url origin https://%s@github.com/aangwie/ditoko.git && git pull origin main 2>&1',
            escapeshellarg($token)
        );

        $process = Process::fromShellCommandline($cmd, base_path());
        $process->setTimeout(300);
        $process->run(function ($type, $buffer) use (&$output) {
            $output[] = trim($buffer);
        });

        $restore = new Process(['git', 'remote', 'set-url', 'origin', 'https://github.com/aangwie/ditoko.git'], base_path());
        $restore->run();

        $outputStr = implode("\n", $output);
        $success = $process->isSuccessful();

        if ($request->wantsJson()) {
            session()->flash('update_output', $outputStr);
            return response()->json([
                'success' => $success,
                'message' => $success ? 'Update berhasil.' : 'Update gagal. Lihat log untuk detail.',
            ]);
        }

        return back()->with('update_output', $outputStr);
    }
}