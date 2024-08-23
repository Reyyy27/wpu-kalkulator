<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class kalkulatorController extends Controller
{
        public function index()
    {
        $perhitungan = DB::table('kalkulator')->get();

        return view('index', ['perhitungan' => $perhitungan]);
    }

    // Menyimpan hasil kalkulasi
        public function simpanPerhitungan(Request $request)
    {
        $request->validate([
            'tipe' => 'required|string',
            'angka_1' => 'required|numeric',
            'angka_2' => 'required|numeric',
            'hasil' => 'required|numeric',
        ]);

        $id = DB::table('kalkulator')->insertGetId([
            'tipe' => $request->input('tipe'),
            'angka_1' => $request->input('angka_1'),
            'angka_2' => $request->input('angka_2'),
            'hasil' => $request->input('hasil'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $perhitungan = DB::table('kalkulator')->where('id', $id)->first();

        return response()->json([
            'id' => $perhitungan->id,
            'tipe' => $perhitungan->tipe,
            'angka_1' => $perhitungan->angka_1,
            'angka_2' => $perhitungan->angka_2,
            'hasil' => $perhitungan->hasil,
            'message' => 'Data berhasil disimpan.',
        ]);
    }

    // Mengedit hasil kalkulasi
        public function editPerhitungan(Request $request, $id)
    {
        $request->validate([
            'angka_1' => 'required|numeric',
            'angka_2' => 'required|numeric',
            'tipe' => 'required|string',
        ]);

        DB::table('kalkulator')
            ->where('id', $id)
            ->update([
                'angka_1' => $request->input('angka_1'),
                'angka_2' => $request->input('angka_2'),
                'tipe' => $request->input('tipe'),
                'hasil' => $this->hitungHasil($request->input('angka_1'), $request->input('angka_2'), $request->input('tipe')),
                'updated_at' => now(),
            ]);

        $perhitungan = DB::table('kalkulator')->where('id', $id)->first();

        return response()->json([
            'id' => $perhitungan->id,
            'tipe' => $perhitungan->tipe,
            'angka_1' => $perhitungan->angka_1,
            'angka_2' => $perhitungan->angka_2,
            'hasil' => $perhitungan->hasil,
            'message' => 'Data berhasil diperbarui.',
        ]);
    }

    // Menghapus hasil kalkulasi
        public function hapusPerhitungan($id)
    {
        DB::table('kalkulator')->where('id', $id)->delete();

        return response()->json(['message' => 'Data berhasil dihapus.']);
    }

    // Menghitung hasil berdasarkan tipe operasi
        private function hitungHasil($angka1, $angka2, $tipe)
    {
        switch ($tipe) {
            case 'tambah':
                return $angka1 + $angka2;
            case 'kurang':
                return $angka1 - $angka2;
            case 'kali':
                return $angka1 * $angka2;
            case 'bagi':
                return $angka2 == 0 ? 'Error' : $angka1 / $angka2;
            default:
                return 0;
        }
    }
}
