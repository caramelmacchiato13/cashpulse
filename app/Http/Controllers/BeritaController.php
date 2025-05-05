<?php

namespace App\Http\Controllers;

// untuk mengakses http
use Illuminate\Support\Facades\Http;

use Illuminate\Http\Request;

class BeritaController extends Controller
{
    // untuk tes response dari API
    public function index()
    {
        $response = Http::get('https://newsapi.org/v2/everything?q=hotel&from=2024-05-08&sortBy=publishedAt&apiKey=0431190d83e94afb97f53b4ffe4564cd');
        $hasil = json_decode($response);
        // var_dump($hasil);

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
        $url = 'https://newsapi.org/v2/everything?q=hotel&from=2024-05-08&sortBy=publishedAt&apiKey=0431190d83e94afb97f53b4ffe4564cd';
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
