<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public static function getRules(): array
    {
        return [
            'name' => 'required|string|max:255'
        ];
    }

    public function dial(): HasMany
    {
        return $this->hasMany(Dial::class, 'category_id', 'id')->orderBy('id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}