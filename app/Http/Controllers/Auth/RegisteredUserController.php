<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'kelompok' => ['required', 'string', 'in:keuangan,admin'], // Ubah nilai sesuai yang dibutuhkan
        ]);

        // Menggunakan DB transaction untuk memastikan kedua operasi berhasil atau gagal bersama
        DB::beginTransaction();
        
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
    
            // Memasukkan data ke tabel users_kelompok
            DB::table('users_kelompok')->insert([
                'id_user' => $user->id,
                'kelompok' => $request->kelompok,
            ]);
            
            DB::commit();
            
            event(new Registered($user));
    
            Auth::login($user);

            session(['kelompok' => $request->kelompok]);
    
            return redirect(RouteServiceProvider::HOME);
        } catch (\Exception $e) {
            DB::rollback();
            
            return back()->withErrors([
                'email' => 'Terjadi kesalahan saat mendaftarkan pengguna: ' . $e->getMessage(),
            ]);
        }
    }
}