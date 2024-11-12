<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class Media extends BaseMedia
{
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_media', 'media_id', 'project_id');
    }
}
