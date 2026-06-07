<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone'      => ['required', 'string', 'max:20'],
            'password'   => ['required', Rules\Password::defaults()],
        ]);

        // 1. Logika Penentuan Jabatan & Role Otomatis
        // Menggunakan str_ends_with agar lebih akurat mengecek domain email
        $email = $request->email;
        $isMlogistix = str_ends_with($email, '@mlogistix.id');

        // Jika @mlogistix.id jadi 'admin', sisanya (biasanya owner/head) jadi 'superadmin'
        // Tidak ada lagi jabatan 'staf'
        $jabatan = $isMlogistix ? 'admin' : 'superadmin';
        $role    = $isMlogistix ? 'admin' : 'superadmin';

        // 2. Simpan data ke database
        $user = User::create([
            'first_name'  => $request->first_name,
            'last_name'   => $request->last_name,
            'email'       => $email,
            'phone'       => $request->phone,
            'password'    => Hash::make($request->password),
            'jabatan'     => $jabatan,    // Disimpan agar bisa tampil di tabel
            'role'        => $role,       // Role sinkron dengan jabatan
            'is_approved' => false,       // Default menunggu ACC
        ]);

        // 3. Trigger event registrasi
        event(new Registered($user));

        // 4. Kembali ke register dengan sinyal modal
        return redirect()->route('register')->with([
            'menunggu_acc' => true,
            'nama_user'    => $user->first_name,
            'email_user'   => $user->email
        ]);
    }

   public function approveUser($id)
{
    $user = User::findOrFail($id);
    $user->update(['is_approved' => true]);

    return redirect()->back()->with('success', 'User berhasil di-ACC!');
}

public function checkApproval($email)
{
    $user = User::where('email', $email)->first();
    return response()->json([
        'is_approved' => $user ? (bool) $user->is_approved : false
    ]);
}
    }

