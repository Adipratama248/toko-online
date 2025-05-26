<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class transaksi extends Model
{
    
    protected $table = 'transaksi';
    protected $primarykey = 'id';
    public $timestamps = false;
    protected $fillable = ['id_barang','jumlah_barang','harga_total','status','tgl_transaksi'];

    public function barang()
{
    return $this->belongsTo(Barang::class, 'id_barang');
}

}
