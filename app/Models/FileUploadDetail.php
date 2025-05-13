<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string title
 * @property string description
 * @property string slug
 * @property int file_upload_id
 * @property FileUpload $fileUpload
 */
class FileUploadDetail extends Model
{
    protected $fillable=['title', 'description', 'slug', 'file_upload_id'];

    public function fileUpload(): BelongsTo
    {
        return $this->belongsTo(FileUpload::class);
    }
}
