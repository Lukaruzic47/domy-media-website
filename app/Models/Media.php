<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media
{
    protected $fillable = ['path'];
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_media', 'media_id', 'project_id');
    }
}
