<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class barangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = DB::select("SELECT * FROM v_barang");
        // Hanya dipakai untuk mengisi pilihan <select> di modal Tambah & Edit
        $satuan = DB::select("SELECT * FROM satuan WHERE status = 1");

        return view('barang.index', compact('data', 'satuan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis'    => 'required|in:B,N,K,A',
            'nama'     => 'required|string|max:45',
            'status'   => 'required|in:0,1',
            'harga'    => 'required|integer|min:0',
            'idsatuan' => 'required|integer',
        ]);

        try {
            DB::insert("INSERT INTO barang (jenis, nama, status, harga, idsatuan) VALUES (?, ?, ?, ?, ?)", [
                $request->jenis,
                $request->nama,
                $request->status,
                $request->harga,
                $request->idsatuan,
            ]);

            return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Gagal menyimpan: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'jenis'    => 'required|in:B,N,K,A',
            'nama'     => 'required|string|max:45',
            'status'   => 'required|in:0,1',
            'harga'    => 'required|integer|min:0',
            'idsatuan' => 'required|integer',
        ]);

        DB::update("UPDATE barang SET jenis = ?, nama = ?, status = ?, harga = ?, idsatuan = ? WHERE idbarang = ?", [
            $request->jenis,
            $request->nama,
            $request->status,
            $request->harga,
            $request->idsatuan,
            $id,
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::delete("DELETE FROM barang WHERE idbarang = ?", [$id]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus');
    }
}