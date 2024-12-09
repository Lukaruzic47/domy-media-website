<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Models\Media;

class Project extends Model implements HasMedia
{
    //
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'title',
        'author',
        'description',
        'date',
        'published',
        'metadata',
        'category',
        'slug',
        'youtube_url',
        'instagram_url',
        'tiktok_url',
    ];

    public function projectMedia()
    {
        return $this->belongsToMany(Media::class, 'project_media', 'project_id', 'media_id');
    }
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')->singleFile();
        $this->addMediaCollection('videos');
    }

    public function registerMediaConversions(\Spatie\MediaLibrary\MediaCollections\Models\Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(300)
            ->height(300)
            ->sharpen(10);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
