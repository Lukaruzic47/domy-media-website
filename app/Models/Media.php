<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;
    protected $fillable = [
        'media_id' => 'string',
        'project_id',
        'path',
        'file_name',
        'mime_type',
        'size',
    ];

    protected $primaryKey = 'media_id';
    public function projects()
    {
        return $this->hasMany(Project::class, 'project_id', 'media_id');
    }
}
