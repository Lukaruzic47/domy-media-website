<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'description',
        'date',
        'thumbnail',
        'published',
        'metadata',
        'category',
        'main_video',
        'slug',
        'youtube_url',
        'instagram_url',
        'tiktok_url',
    ];

    public function projectMedia()
    {
        return $this->belongsToMany(Media::class, 'project_media', 'project_id', 'media_id');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
    public static function slugExists($slug): bool
    {
        if(Project::where('slug', $slug)->exists()) {
            return true;
        }
        else {
            return false;
        }
    }

}
