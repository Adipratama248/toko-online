<?php

namespace App\Http\Controllers;

use App\Models\barang;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function createB(Request $req)  {
        $validator = Validator::make($req->all(),[
            'nama'=>'required',
            'harga'=>'required',
            'stok'=>'required',
            'foto'=>'required',
            'deskripsi'=>'required',

        ]);
        if($validator->fails()){
            return Response()->json($validator->errors()->toJson());
        }

        if($req->hasFile('foto')){
            $file = $req->file('foto');
            $filename = time().'_'. $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $foto='uploads/'.$filename;
        } else {
            $foto = null;
        }

        $save = barang::create([
            'nama' => $req->get('nama'),
            'harga' => $req->get('harga'),
            'stok' => $req->get('stok'),
            'deskripsi' => $req->get('deskripsi'),
            'foto' => $foto,
        ]);
        if($save){
            return Response()->json(['status'=>true, 'message'=>'Sukses update barang']);
        } else{
            return Response()->json(['status'=>false, 'message'=>'Gagal update barang']);
        }
    }

    public function updateB(Request $req, $id)
    {

        $validaator = Validator::make($req->all(), [
            'nama' => 'required',
            'harga' => 'required',
            'stok' => 'required',
            'deskripsi' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    if($validaator->fails()){
        return response()->json($validaator->errors(),400);
    }

    $barang = barang::find($id);
    if (!$barang) {
        return response()->json(['status' => false, 'message' => 'Barang tidak ditemukan'], 404);
    }

        $barang->nama = $req->nama;
        $barang->harga    = $req->harga;
        $barang->stok   = $req->stok;

        // Cek apakah ada foto baru
        if ($req->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($barang->foto) {
                $oldImagePath = public_path($barang->foto);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // Upload foto baru
            $file = $req->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $barang->foto = 'uploads/' . $filename;
        }

        $barang->save();

        return response()->json([
            'status'  => true,
            'message' => 'Barang berhasil diperbarui',
            'barang'    => $barang
        ]);
    }

    public function deleteB($id){
        $barang = barang::find(id: $id);

        if(!$barang) {
            return response()->json(['status'=>false, 'message'=> "Barang dengan id $id tidak ditemukan"],404);
        }

        $barang->delete();

        return response()->json(['status'=>true, 'message'=>'Barang berhasil dihapus']);
    }

    public function show($id) {
        $barang = barang::find($id);
        if (!$barang) {
            return response()->json(['status' => false, 'message' => 'Barang tidak ditemukan'], 404);
        }
        return response()->json($barang);
    }

    public function showall()
    {
        // Menggunakan Eloquent
        $barang = barang::all();
        return response()->json($barang);
    }

}
