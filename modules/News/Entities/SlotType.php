<?php

namespace Modules\News\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SlotType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'is_active', 'lang'];
    
    protected static function newFactory()
    {
        return \Modules\News\Database\factories\SlotTypeFactory::new();
    }
}
