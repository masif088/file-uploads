<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string title
 * @property string description
 * @property string slug
 * @property FileUploadDetail[] $fileUploadDetails
 */
class FileUpload extends Model
{
    protected $fillable =['title', 'description', 'slug'];

    public function details(): HasMany
    {
        return $this->hasMany(FileUploadDetail::class);
    }
}
