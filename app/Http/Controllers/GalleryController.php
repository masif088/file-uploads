<?php

namespace App\Http\Controllers;

use App\Models\FileUpload;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function show($slug)
    {
        $upload = FileUpload::where('slug', $slug)->with('details')->firstOrFail();

        return view('gallery.show', compact('upload'));
    }
}
