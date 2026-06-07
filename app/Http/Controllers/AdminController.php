<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AdminController extends Controller
{
    // 1. Khusus Dashboard Super Admin
    public function superAdminDashboard()
    {
        $hasItems = Schema::hasTable('items');
        $hasTransactions = Schema::hasTable('transactions');

        $data = [
            'users' => User::where('role', '!=', 'superadmin')->get(),
            // Tambahkan pending_users agar notif muncul kembali
            'pending_users' => User::where('is_approved', false)->get(),
            'total_users' => User::count(),
            'total_items' => $hasItems ? Item::count() : 0,
            'total_stock' => $hasItems ? Item::sum('stok') : 0,
            'total_transaksi' => $hasTransactions ? Transaction::count() : 0,
            'admin_aktif' => User::where('role', 'admin')->where('is_approved', true)->count()
        ];

        return view('admin.dashboard', $data);
    }

    // 2. Khusus Dashboard Admin Biasa
    public function index()
{
    // Pastikan kita mengambil semua kolom user
    $users = \App\Models\User::all();

    // Pastikan variabel 'users' ini dikirim ke view
    return view('dashboard', compact('users'));
}

    // --- FUNGSI MANAJEMEN USER ---

   public function updateJabatan(Request $request, $id)
{
    // Batasi validasi hanya untuk admin dan superadmin
    $request->validate([
        'jabatan' => 'required|in:superadmin,admin',
    ]);

    $user = User::findOrFail($id);
    $user->jabatan = $request->jabatan;
    $user->role = $request->jabatan; // Pastikan role juga sinkron
    $user->save();

    return back()->with('success', 'Jabatan diperbarui.');
}

    public function toggle($id)
    {
        $user = User::findOrFail($id);
        if ($user->role === 'superadmin') return back();
        $user->is_approved = !$user->is_approved;
        $user->save();
        return back();
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        if ($user->role === 'superadmin') return back();
        $user->delete();
        return back();
    }

    public function role($id)
    {
        $user = User::findOrFail($id);
        if ($user->role === 'superadmin') return back();
        $user->role = ($user->role === 'admin') ? 'staf' : 'admin';
        $user->save();
        return back();
    }
}
