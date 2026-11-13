<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class satuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = DB::select("SELECT * FROM v_satuan");
        return view('satuan.index', compact('data'));
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
            'nama_satuan' => 'required|string|max:45',
            'status'      => 'required|in:0,1',
        ]);

        try {
            $nextId = DB::selectOne("SELECT COALESCE(MAX(idsatuan), 0) + 1 AS id FROM satuan")->id;

            DB::insert("INSERT INTO satuan (idsatuan, nama_satuan, status) VALUES (?, ?, ?)", [
                $nextId,
                $request->nama_satuan,
                $request->status,
            ]);

            return redirect()->route('satuan.index')->with('success', 'Satuan berhasil ditambahkan');
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
            'nama_satuan' => 'required|string|max:45',
            'status'      => 'required|in:0,1',
        ]);

        try {
            DB::update("UPDATE satuan SET nama_satuan = ?, status = ? WHERE idsatuan = ?", [
                $request->nama_satuan,
                $request->status,
                $id,
            ]);

            return redirect()->route('satuan.index')->with('success', 'Satuan berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Gagal memperbarui: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::delete("DELETE FROM satuan WHERE idsatuan = ?", [$id]);

            return redirect()->route('satuan.index')->with('success', 'Satuan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Satuan tidak dapat dihapus karena masih digunakan pada data barang.']);
        }
    }
}
