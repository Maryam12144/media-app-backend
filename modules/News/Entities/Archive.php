<?php

namespace Modules\News\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Archive extends Model
{
    use HasFactory;

    protected $fillable = ['video_name','video_path','video_type'];
    
    protected static function newFactory()
    {
        return \Modules\News\Database\factories\ArchiveFactory::new();
    }
}
