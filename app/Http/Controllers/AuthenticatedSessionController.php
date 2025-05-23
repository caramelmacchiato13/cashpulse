<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


use App\Models\Akses; //load model dari kelas model akses

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        // // Tambahan untuk menambah variabel session kelompok
        // $id_customer = Auth::id(); //dapatkan id dari session yang sudah tercreate
        // $akses = Akses::getGrupUser($id_customer);
        // foreach($akses as $p):
        //     $kelompok = $p->kelompok;
        // endforeach;
        // // membuat session dengan nama variabel kelompok
        // session(['kelompok' => $kelompok]);
        // dump($kelompok);
        // // Akhir tambahan variabel session kelompok

        // return redirect()->intended(RouteServiceProvider::HOME);

        $user = Auth::user();
        $kelompok = DB::table('users_kelompok')
        ->where('id_user', $user->id)
        ->value('kelompok');
    
        Session::put('kelompok', $kelompok);

    return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
