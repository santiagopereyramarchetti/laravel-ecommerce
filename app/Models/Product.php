<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $guarded = [];

    public function categories(): BelongsToMany{
        return $this->belongsToMany(Category::class);
    }

    public function creator(): BelongsTo{
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function scopeActive($builder){
        return $builder->where('active', true);
    }

    public function scopeInActive($builder){
        return $builder->where('active', false);
    }
}
