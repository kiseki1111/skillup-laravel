<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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

    public function completedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'lecture_user', 'lecture_id', 'user_id');
    }
}
