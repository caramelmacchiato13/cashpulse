<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\KasKeluar;
use App\Http\Requests\StorePembayaranRequest;
use App\Http\Requests\UpdatePembayaranRequest;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB; // untuk query 
use Illuminate\Support\Facades\Auth; //untuk mendapatkan auth
use Illuminate\Support\Facades\Validator; //untuk validasi

class PembayaranController extends Controller
{
    public function cekstatus(){
    
        //query data transaksi yang masih pending	
		$hasil = Pembayaran::viewstatusPGAll();
        $order = array();
        $id = array();
		foreach($hasil as $ks){
			array_push($order,$ks->order_id);
			array_push($id,$ks->id);
		}
        for($i=0; $i<count($order); $i++){
            $ch = curl_init(); 
            $login = env('MIDTRANS_SERVER_KEY');
            $password = '';
            $orderid = $order[$i];
            $id = $id[$i];
            $URL =  'https://api.sandbox.midtrans.com/v2/'.$orderid.'/status';
            curl_setopt($ch, CURLOPT_URL, $URL);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");  
            $output = curl_exec($ch); 
            curl_close($ch);    
            $outputjson = json_decode($output, true); //parsing json dalam bentuk assosiative array
            // var_dump($outputjson);

            $affected = DB::update(
                'update pg_pembayaran 
                 set status_code = ?,
                     transaction_status = ?,
                     transaction_time = ?,
                     settlement_time = ?,
                     status_message = ?,
                     merchant_id = ?
                 where order_id = ?',
                [
                    $outputjson['status_code'],
                    $outputjson['transaction_status'],
                    $outputjson['transaction_time'],
                    $outputjson['settlement_time'],
                    $outputjson['status_message'],
                    $outputjson['merchant_id'],
                    $orderid
                ]
            );

            // simpan data
            $empData = ['no_transaksi' => $orderid, 'tgl_bayar' => $outputjson['transaction_time'], 'jenis_pembayaran' => 'pg', 'status' => 'approved'];
            Pembayaran::create($empData);

            // update status
            $affected = DB::table('kaskeluar')
              ->where('id', $id)
              ->update(['status_pembayaran' => 'approved']);
        }

        return view('pembayaran/autorefresh');
    }

    // bayar
    public function bayar($id){
        $kaskeluar = KasKeluar::find($id);

            \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
            \Midtrans\Config::$isProduction = false;
            // Set sanitization on (default)
            \Midtrans\Config::$isSanitized = true;
            // Set 3DS transaction for credit card to true
            \Midtrans\Config::$is3ds = true;

            $params = array(
                'transaction_details' => array(
                    'order_id' => rand(), //idpesanan ini nanti bisa diambil dari no_pesanan
                    'gross_amount' => intval($kaskeluar->jumlah), //gross amount diisi total tagihan
                ),
                'customer_details' => array(
                    'first_name' => Auth::user()->name,
                    'last_name' => '',
                    'email' => Auth::user()->email,
                    'phone' => '',
                ),
            );
            
            $snapToken = \Midtrans\Snap::getSnapToken($params);


            return view('pembayaran.viewdetail',
                        [
                            'snap_token' => $snapToken,
                            'id_kaskeluar' => $kaskeluar->id,
                            'nama_projek' => $kaskeluar->nama_projek,
                            'jumlah' => $kaskeluar->jumlah
                        ]
                    );
    }

    // proses bayar
    public function proses_bayar(Request $request){
        
        $json  = json_decode($request->x_json);


        $id_kaskeluar = $request->input('id_kaskeluar');
        $order_id = $json->order_id;
        $gross_amount = $json->gross_amount;
        $transaction_status = $json->transaction_status;
        $transaction_id = $json->transaction_id;
        $payment_type = $json->payment_type;
        $status_code = $json->status_code;
        DB::insert('insert into pg_pembayaran (id_kaskeluar, order_id, gross_amount, transaction_id, payment_type, status_code) values (?, ?, ?, ?, ?, ?)', [$id_kaskeluar, $order_id, $gross_amount, $transaction_id, $payment_type, $status_code]);

        return redirect('kaskeluar');
    }
}
