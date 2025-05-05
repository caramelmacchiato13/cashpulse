<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Grafik;

class GrafikController extends Controller
{
    public function kasmasuk(){
        $grafik = Grafik::kasmasuk();
        return view('grafik.kasmasuk',
                        [
                            'grafik' => $grafik
                        ]
                    );
    }

    public function kaskeluar(){
        $grafik = Grafik::kaskeluar();
        return view('grafik.kaskeluar',
                        [
                            'grafik' => $grafik
                        ]
                    );
    }
}
