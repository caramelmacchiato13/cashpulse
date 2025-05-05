<?php

namespace App\Http\Controllers;

// untuk mengakses http
use Illuminate\Support\Facades\Http;

use Illuminate\Http\Request;

class Berita1Controller extends Controller
{
    // untuk tes response dari API
    public function index()
    {
        $response = Http::get('https://newsapi.org/v2/everything?q=bola&from=2022-05-05$to=2024-10-04&sortBy=publishedAt&apiKey=d37fd9ba6c1e49e8b1ce9e8c39ae775e');
        $hasil = json_decode($response);
        var_dump($hasil);

        if($hasil->status=="ok"){
            echo "Jumlah Status     : ".$hasil->status."<br>";
            echo "Jumlah Results    : ".$hasil->totalResults."<br>";
            echo "Sumber Artikel-1  : ".$hasil->articles[0]->source->name."<br>";
            echo "Nama Artikel-2    : ".$hasil->articles[1]->title."<br>";
            echo "URL Gambar        : ".$hasil->articles[1]->urlToImage."<br>";

            // dapatkan jumlah datanya
            echo "<hr>";
            foreach ($hasil->articles as $row){
                echo $row->source->name."-".$row->author."-".$row->title."-".$row->url."-".$row->description."-".$row->urlToImage;
                echo "<br>"; 
            } 
               
        }
    }

    // untuk galeri berita
    public function getNews(){
        // akses API
        $url = 'https://newsapi.org/v2/everything?q=projek&from=2022-05-05$to=2024-10-04&sortBy=publishedAt&apiKey=d37fd9ba6c1e49e8b1ce9e8c39ae775e';
        $response = Http::get($url);
        $hasil = json_decode($response);
        // var_dump($hasil);
        return view(
            'berita.berita',
            [
                'hasil' => $hasil
            ]
        );
    }
}
