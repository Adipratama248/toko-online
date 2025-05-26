<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Validator;

class PembayaranController extends Controller
{
    public function addP(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'id_transaksi' => 'required|exists:transaksi,id',
            'status' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Cek transaksi
        $transaksi = Transaksi::find($request->id_transaksi);

        if (!$transaksi) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        // Buat pembayaran
        $pembayaran = Pembayaran::create([
            'id_transaksi' => $request->id_transaksi,
            'status' => $request->status,
        ]);

        // Jika status pembayaran adalah lunas, update status transaksi juga
        if (strtolower($request->status) === 'lunas') {
            $transaksi->status = 'lunas';
            $transaksi->save();
        }

        return response()->json([
            'message' => 'Pembayaran berhasil dicatat',
            'pembayaran' => $pembayaran,
        ], 201);
    }

    // Ambil semua pembayaran
public function getAllP()
{
    $pembayaran = Pembayaran::with('transaksi')->get();

    return response()->json([
        'status' => true,
        'message' => 'Semua data pembayaran berhasil diambil',
        'data' => $pembayaran
    ]);
}

// Ambil pembayaran berdasarkan ID
public function getByIdP($id)
{
    $pembayaran = Pembayaran::with('transaksi')->find($id);

    if (!$pembayaran) {
        return response()->json([
            'status' => false,
            'message' => 'Pembayaran tidak ditemukan'
        ], 404);
    }

    return response()->json([
        'status' => true,
        'message' => 'Detail pembayaran berhasil diambil',
        'data' => $pembayaran
    ]);
}

// Hapus pembayaran
public function deleteP($id)
{
    $pembayaran = Pembayaran::find($id);

    if (!$pembayaran) {
        return response()->json([
            'status' => false,
            'message' => 'Pembayaran tidak ditemukan'
        ], 404);
    }

    $pembayaran->delete();

    return response()->json([
        'status' => true,
        'message' => 'Pembayaran berhasil dihapus'
    ]);
}

}
