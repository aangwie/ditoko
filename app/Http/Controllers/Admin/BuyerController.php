<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class BuyerController extends Controller
{
    public function index()
    {
        $buyers = User::where('role', 'buyer')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.buyers.index', compact('buyers'));
    }

    public function data(Request $request)
    {
        $buyers = User::where('role', 'buyer');

        if ($request->search) {
            $search = $request->search;
            $buyers->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('bank', 'like', "%{$search}%")
                  ->orWhere('bank_number', 'like', "%{$search}%");
            });
        }

        $sortField = $request->sort_field ?? 'created_at';
        $sortDir = $request->sort_dir ?? 'desc';
        $buyers->orderBy($sortField, $sortDir);

        $perPage = $request->per_page ?? 10;
        $buyers = $buyers->paginate($perPage);

        return response()->json($buyers);
    }
}