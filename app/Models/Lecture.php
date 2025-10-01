<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Lecture extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'section_id',
        'content',
        'video_path',
        'thumbnail_path',
        'duration_in_seconds',
    ];

    public function section(): BelongsTo{
        return $this->belongsTo(Section::class);
    }   
}
