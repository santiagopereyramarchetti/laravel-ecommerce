<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'active'
    ];

    protected $cast = [
        'active' => 'boolean'
    ];

    public function children(): HasMany{
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function parent(): BelongsTo{
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function products(): BelongsToMany{
        return $this->belongsToMany('products');
    }

    public function scopeRoot($builder){
        return $builder->whereNull('parent_id');
    }

    public function scopeActive($builder){
        return $builder->where('active', true);
    }

    public function scopeInActive($builder){
        return $builder->where('active', false);
    }
}
