<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class barang extends Model
{
    
    protected $table = 'barang';
    protected $primarykey = 'id';
    public $timestamps = false;
    protected $fillable = ['nama','harga','stok','foto','deskripsi'];

    public function transaksi()
    {
        return $this->hasMany(transaksi::class, 'id_barang');
    }
}
