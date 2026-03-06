<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
// READ ALL: Mengambil semua daftar video
    public function index()
    {
        $videos = Video::all();
        return response()->json($videos, 200);
    }

    // CREATE: Menambahkan video baru
    public function store(Request $request)
    {
        // $request->all() akan mengambil TITLE, VIDEO_URL, dan DURASI dari Postman
        $video = Video::create($request->all()); 
        return response()->json($video, 201);
    }

    // READ SINGLE: Melihat detail satu video berdasarkan ID
    public function show($id)
    {
        $video = Video::findOrFail($id);
        return response()->json($video, 200);
    }

    // UPDATE: Mengubah data video yang sudah ada
    public function update(Request $request, $id)
    {
        $video = Video::findOrFail($id);
        $video->update($request->all());
        return response()->json($video, 200);
    }

    // DELETE: Menghapus video dari database
    public function destroy($id)
    {
        Video::destroy($id);
        // 204 No Content adalah standar sukses untuk penghapusan
        return response()->json(null, 204);
    }
}
    // /**
    //  * Display a listing of the resource.
    //  */
    // public function index()
    // {
    //     //
    // }

    // /**
    //  * Show the form for creating a new resource.
    //  */
    // public function create()
    // {
    //     //
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  */
    // public function store(Request $request)
    // {
    //     //
    // }

    // /**
    //  * Display the specified resource.
    //  */
    // public function show(Video $video)
    // {
    //     //
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  */
    // public function edit(Video $video)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request, Video $video)
    // {
    //     //
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(Video $video)
    // {
    //     //
    // }
