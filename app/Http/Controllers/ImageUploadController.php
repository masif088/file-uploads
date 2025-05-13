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
            'title' => 'nullable|string|max:255',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240', // max 10MB per file
        ]);

        $fu = FileUpload::create([
            'title' => $request->title,
            'description' => $request->description,
            'slug' => uniqid(),
        ]);

        $paths = [];
        $outputPath = 'storage/uploads/';

        foreach ($request->file('images') as $image) {
            $originalName = $image->getClientOriginalName();
            $filename = $request->title . '-' . uniqid() . '-' . $originalName;
            $fullPath = public_path($outputPath . $filename);

            // Load image using GD
            $img = null;
            $mime = $image->getMimeType();
            switch ($mime) {
                case 'image/jpeg':
                    $img = imagecreatefromjpeg($image->getPathname());
                    break;
                case 'image/png':
                    $img = imagecreatefrompng($image->getPathname());
                    break;
                case 'image/gif':
                    $img = imagecreatefromgif($image->getPathname());
                    break;
                default:
                    continue 2; // skip this file
            }

            // Resize if width > 1080
            $width = imagesx($img);
            $height = imagesy($img);

            if ($width > 920) {
                $newWidth = 920;
                $newHeight = intval(($newWidth / $width) * $height);
                $resizedImg = imagecreatetruecolor($newWidth, $newHeight);

                // Preserve transparency for PNG/GIF
                if ($mime === 'image/png' || $mime === 'image/gif') {
                    imagecolortransparent($resizedImg, imagecolorallocatealpha($resizedImg, 0, 0, 0, 127));
                    imagealphablending($resizedImg, false);
                    imagesavealpha($resizedImg, true);
                }

                imagecopyresampled($resizedImg, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                imagedestroy($img);
                $img = $resizedImg;
            }

            // Save resized image
            switch ($mime) {
                case 'image/jpeg':
                    imagejpeg($img, $fullPath, 85);
                    break;
                case 'image/png':
                    imagepng($img, $fullPath);
                    break;
                case 'image/gif':
                    imagegif($img, $fullPath);
                    break;
            }

            imagedestroy($img);

            FileUploadDetail::create([
                'title' => $filename,
                'description' => $request->description . '-' . $filename,
                'slug' => $outputPath . $filename,
                'file_upload_id' => $fu->id,
            ]);

            $paths[] = $outputPath . $filename;
        }

        return response()->json([
            'message' => 'Images uploaded and resized successfully.',
            'link' => env('APP_URL') . '/gallery/' . $fu->slug
        ]);
    }
}
