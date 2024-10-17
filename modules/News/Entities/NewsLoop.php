<?php

namespace Modules\News\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsLoop extends Model
{
    use SoftDeletes;

    protected $fillable = ['loop_count', 'loop_duration'];
    
  
}
