<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table = 'member';
    protected $primaryKey = 'id_member';
    public $timestamps = false;

    protected $fillable = [
        'id_member',
        'nama_member',
        'alamat',
        'jenis_kelamin',
        'no_telp',
    ];
}
