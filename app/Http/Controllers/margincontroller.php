<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class marginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = DB::select("SELECT * FROM v_margin");
        return view('margin.index', compact('data'));
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
            'persen' => 'required|numeric',
            'status' => 'required|in:0,1',
        ]);

        $iduser = session('iduser') ?? 1; // Sesuaikan dengan session user yang login

        // Jika status aktif (1), non-aktifkan margin lain agar hanya ada 1 margin aktif
        if ($request->status == 1) {
            DB::update("UPDATE margin_penjualan SET status = 0");
        }

        DB::insert("INSERT INTO margin_penjualan (persen, status, iduser, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())", [
            $request->persen,
            $request->status,
            $iduser
        ]);

        return redirect()->route('margin.index')->with('success', 'Margin berhasil ditambahkan');
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
            'persen' => 'required|numeric',
            'status' => 'required|in:0,1',
        ]);

        $iduser = session('iduser') ?? 1;

        if ($request->status == 1) {
            DB::update("UPDATE margin_penjualan SET status = 0 WHERE idmargin_penjualan != ?", [$id]);
        }

        DB::update("UPDATE margin_penjualan SET persen = ?, status = ?, iduser = ?, updated_at = NOW() WHERE idmargin_penjualan = ?", [
            $request->persen,
            $request->status,
            $iduser,
            $id
        ]);

        return redirect()->route('margin.index')->with('success', 'Margin berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::delete("DELETE FROM margin_penjualan WHERE idmargin_penjualan = ?", [$id]);

        return redirect()->route('margin.index')->with('success', 'Margin berhasil dihapus');
    }
}