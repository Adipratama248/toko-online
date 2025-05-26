<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\transaksi;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function createT(Request $request)  {
        $validator = Validator::make($request->all(), [
            'id_barang' => 'required|exists:barang,id',
            'jumlah_barang' => 'required|integer|min:1',
            'status' => 'required|in:belum bayar,lunas',
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    
        $barang = Barang::find($request->id_barang);
    
        // Cek stok cukup
        if ($barang->stok < $request->jumlah_barang) {
            return response()->json([
                'status' => false,
                'message' => 'Stok tidak mencukupi'
            ], 400);
        }
    
        // Hitung harga total
        $harga_total = $barang->harga * $request->jumlah_barang;
    
        // Kurangi stok barang
        $barang->stok -= $request->jumlah_barang;
        $barang->save();
    
        // Simpan penjualan
        $penjualan = transaksi::create([
            'id_barang' => $barang->id,
            'jumlah_barang' => $request->jumlah_barang,
            'harga_total' => $harga_total,
            'status' => $request->status,
            'tgl_transaksi' => now(),
        ]);
    
        return response()->json([
            'status' => true,
            'message' => 'Transaksi berhasil',
            'data' => $penjualan
        ], 201);
    }

    public function editT(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'id_barang' => 'required|exists:barang,id',
        'jumlah_barang' => 'required|integer|min:1',
        'status' => 'nullable|in:belum bayar,lunas',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    $transaksi = Transaksi::find($id);
    if (!$transaksi) {
        return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
    }

    // Kembalikan stok lama
    $barangLama = Barang::find($transaksi->id_barang);
    $barangLama->stok += $transaksi->jumlah_barang;
    $barangLama->save();

    // Kurangi stok baru
    $barangBaru = Barang::find($request->id_barang);
    if ($barangBaru->stok < $request->jumlah_barang) {
        return response()->json(['message' => 'Stok tidak mencukupi untuk barang yang dipilih'], 400);
    }

    $barangBaru->stok -= $request->jumlah_barang;
    $barangBaru->save();

    // Update data transaksi
    $transaksi->id_barang = $barangBaru->id;
    $transaksi->jumlah_barang = $request->jumlah_barang;
    $transaksi->harga_total = $barangBaru->harga * $request->jumlah_barang;

    // Update status hanya jika dikirim
    if ($request->has('status')) {
        // Jika status diganti menjadi 'belum bayar', hapus data pembayaran
        if ($request->status === 'belum bayar') {
            \App\Models\Pembayaran::where('id_transaksi', $transaksi->id)->delete();
        }

        $transaksi->status = $request->status;
    }

    $transaksi->save();

    return response()->json([
        'status' => true,
        'message' => 'Transaksi berhasil diperbarui',
        'data' => $transaksi
    ]);
}
     

public function getAllT()
{
    $data = Transaksi::with('barang')->get();

    return response()->json([
        'status' => true,
        'message' => 'Data transaksi berhasil diambil',
        'data' => $data
    ]);
}

public function getByIdT($id)
{
    $transaksi = Transaksi::with('barang')->find($id);

    if (!$transaksi) {
        return response()->json([
            'status' => false,
            'message' => 'Transaksi tidak ditemukan'
        ], 404);
    }

    return response()->json([
        'status' => true,
        'message' => 'Detail transaksi berhasil diambil',
        'data' => $transaksi
    ]);
}


public function deleteT($id)
{
    $transaksi = Transaksi::find($id);

    if (!$transaksi) {
        return response()->json([
            'status' => false,
            'message' => 'Transaksi tidak ditemukan'
        ], 404);
    }

    // Kembalikan stok ke barang
    $barang = Barang::find($transaksi->id_barang);
    if ($barang) {
        $barang->stok += $transaksi->jumlah_barang;
        $barang->save();
    }

    $transaksi->delete();

    return response()->json([
        'status' => true,
        'message' => 'Transaksi berhasil dihapus'
    ]);
}


}
