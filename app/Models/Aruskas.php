<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aruskas extends Model
{
    protected $table = 'aruskas';
    protected $fillable = ['periode','projek_id', 'kas_awal', 'kas_akhir'];
    protected $dates = ['periode'];
}