<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class GalleryController extends Controller
{
    public function index() 
    {
        return view('gallery');
    }

    public function generateImages(Request $request)
{
    $count = $request->input('count');

    // Query to get the image URLs from the database
    $images = DB::table('images')->inRandomOrder()->limit($count)->get();

    // Format the image URLs correctly
    $images = $images->map(function ($image) {
        $image->url = asset('storage/' . $image->url); // Ensure the URL is correctly formatted
        return $image;
    });

    // Return response JSON
    return response()->json(['images' => $images]);
}


    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg|max:2048',
        ]);

        $file = $request->file('image');
        $path = $file->store('images', 'public');

        DB::table('images')->insert([
            'url' => $path,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Image uploaded successfully.');
    }

        public function showImage($id)
    {
        $image = DB::table('images')->where('id', $id)->first();
        if (!$image) {
            abort(404);
        }

        return response($image->data)
            ->header('Content-Type', 'image/jpg'); // Ubah sesuai dengan tipe gambar
    }
}
