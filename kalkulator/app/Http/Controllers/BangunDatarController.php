<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BangunDatarController extends Controller
{
    public function index(Request $request)
{
    // Mulai query
    $query = DB::table('bangun_datar');

    // Filter berdasarkan nama bangun datar
    if ($request->filled('nama_bangun')) {
        $query->where('nama_bangun', $request->input('nama_bangun'));
    }

    // Filter berdasarkan hasil terbesar/terkecil
    if ($request->filled('hasil')) {
        if ($request->input('hasil') == 'terbesar') {
            $query->orderBy('hasil', 'desc');
        } elseif ($request->input('hasil') == 'terkecil') {
            $query->orderBy('hasil', 'asc');
        }
    }

    // Filter berdasarkan angka_1
    if ($request->filled('angka1')) {
        $operator1 = $request->input('operator1', '=');
        $query->where('angka_1', $operator1, $request->input('angka1'));
    }

    // Filter berdasarkan angka_2
    if ($request->filled('angka2')) {
        $operator2 = $request->input('operator2', '=');
        $query->where('angka_2', $operator2, $request->input('angka2'));
    }

    // Ambil data hasil query
    $bangunDatar = $query->get();

    return view('luas', ['bangunDatar' => $bangunDatar]);
}


    public function simpanBangunDatar(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'nama_bangun' => 'required|string',
            'angka_1' => 'required|numeric',
            'angka_2' => 'required|numeric',
            'hasil' => 'required|numeric',
        ]);

        // Simpan data ke dalam database
        $id = DB::table('bangun_datar')->insertGetId([
            'nama_bangun' => $request->input('nama_bangun'),
            'angka_1' => $request->input('angka_1'),
            'angka_2' => $request->input('angka_2'),
            'hasil' => $request->input('hasil'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Ambil data yang baru saja disimpan
        $bangunDatar = DB::table('bangun_datar')->where('id', $id)->first();

        return response()->json([
            'id' => $bangunDatar->id,
            'nama_bangun' => $bangunDatar->nama_bangun,
            'angka_1' => $bangunDatar->angka_1,
            'angka_2' => $bangunDatar->angka_2,
            'hasil' => $bangunDatar->hasil,
            'message' => 'Data berhasil disimpan.',
        ]);
    }

    public function editBangunDatar(Request $request, $id)
{
    $validated = $request->validate([
        'nama_bangun' => 'required|string',
        'angka_1' => 'required|numeric',
        'angka_2' => 'required|numeric',
    ]);

    $hasil = $this->hitungHasil($request->input('angka_1'), $request->input('angka_2'), $request->input('nama_bangun'));

    DB::table('bangun_datar')
        ->where('id', $id)
        ->update([
            'nama_bangun' => $request->input('nama_bangun'),
            'angka_1' => $request->input('angka_1'),
            'angka_2' => $request->input('angka_2'),
            'hasil' => $hasil,
            'updated_at' => now(),
        ]);

    $bangunDatar = DB::table('bangun_datar')->where('id', $id)->first();

    return response()->json([
        'id' => $bangunDatar->id,
        'nama_bangun' => $bangunDatar->nama_bangun,
        'angka_1' => $bangunDatar->angka_1,
        'angka_2' => $bangunDatar->angka_2,
        'hasil' => $bangunDatar->hasil,
        'message' => 'Data berhasil diperbarui.',
    ]);
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

    private function hitungHasil($angka1, $angka2, $tipe)
    {
        switch ($tipe) {
            case 'Persegi':
                return $angka1 * $angka2;
            case 'Persegi Panjang':
                return $angka1 * $angka2;
            case 'Segitiga':
                return 0.5 * $angka1 * $angka2;
            case 'Jajar Genjang':
                return $angka1 * $angka2;
            case 'Belah Ketupat':
                return 0.5 * $angka1 * $angka2;
            default:
                return 0;
        }
    }
}
