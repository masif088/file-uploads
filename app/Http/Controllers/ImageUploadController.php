<?php

namespace App\Http\Controllers;

use App\Models\FileUpload;
use App\Models\FileUploadDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class ImageUploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'title'=>'nullable|string|max:255',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240', // max 10MB per file
        ]);

        $paths = [];

        $fu = FileUpload::create([
            'title' => $request->title,
            'description' => $request->description,
            'slug' => uniqid(),
        ]);


        foreach ($request->file('images') as $image) {
            // Generate unique name
            $filename = $request->title.'-'.uniqid() . '-' . $image->getClientOriginalPath();
            $path = 'storage/uploads/';

            // Save to storage/app/public/uploads
            $image->move(public_path($path), $filename);
//                    return $fu->id;
            FileUploadDetail::create([
                'title' => $filename,
                'description' => $request->description.'-'.$filename,
                'slug' => $path.$filename,
                'file_upload_id' => $fu->id,
            ]);

            $paths[] = Storage::url($path);
        }

        return response()->json([
            'message' => 'Images uploaded successfully.',
            'link'=>env('APP_URL').'/gallery/'.$fu->slug
        ]);
    }
}
