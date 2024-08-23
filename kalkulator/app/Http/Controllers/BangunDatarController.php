<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BangunDatarController extends Controller
{
    public function index()
    {
        // Mengambil semua data dari tabel 'bangun_datar'
        $bangunDatar = DB::table('bangun_datar')->get();
        // Mengirim data ke view
        return view('luas', ['bangunDatar' => $bangunDatar]);
    }

    public function simpanBangunDatar(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'id' => 'nullable|integer',
            'nama_bangun' => 'required|string',
            'angka_1' => 'required|numeric',
            'angka_2' => 'required|numeric',
            'hasil' => 'required|numeric',
        ]);

        if ($request->input('id')) {
            // Update data
            $updated = DB::table('bangun_datar')
                ->where('id', $request->input('id'))
                ->update($validated);

            if ($updated) {
                return response()->json(['message' => 'Data berhasil diperbarui.']);
            } else {
                return response()->json(['message' => 'Data tidak ditemukan.'], 404);
            }
        } else {
            // Simpan data baru
            $id = DB::table('bangun_datar')->insertGetId($validated);
            return response()->json(['message' => 'Data berhasil disimpan.', 'id' => $id]);
        }
    }

    public function editBangunDatar(Request $request, $id)
{
    // Validasi data
    $validated = $request->validate([
        'nama_bangun' => 'required|string',
        'angka_1' => 'required|numeric',
        'angka_2' => 'required|numeric',
        'hasil' => 'required|numeric',
    ]);

    // Update data
    $updated = DB::table('bangun_datar')
                ->where('id', $id)
                ->update($validated);

    if ($updated) {
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    } else {
        return response()->json(['message' => 'Data tidak ditemukan.'], 404);
    }
}



    public function destroy($id)
    {
        // Hapus data
        $deleted = DB::table('bangun_datar')->where('id', $id)->delete();

        if ($deleted) {
            return response()->json(['success' => true, 'message' => 'Data berhasil dihapus.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.'], 404);
        }
    }
}
