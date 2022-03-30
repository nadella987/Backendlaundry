<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail_Transaksi extends Model
{
    protected $table = 'detail_transaksi';
    protected $primaryKey = 'id_detail_transaksi';
    public $timestamps = false;

    protected $fillable = [
    	'id_detail_transaksi',
        'id_transaksi',
        'id_paket',
        'qty',
        'subtotal',
    ];
}
