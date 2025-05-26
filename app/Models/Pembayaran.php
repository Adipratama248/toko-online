<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class pembayaran extends Model
{
    
    protected $table = 'pembayaran';
    protected $primarykey = 'id';
    public $timestamps = false;
    protected $fillable = ['id_transaksi','tgl_pembayaran','status'];

    public function transaksi()
{
    return $this->belongsTo(Transaksi::class, 'id_transaksi');
}

}
