<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['slug', 'label'];

    public function reviews(): BelongsToMany
    {
        return $this->belongsToMany(Review::class, 'review_tag');
    }
}
