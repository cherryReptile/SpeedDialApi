<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Dial
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property bool $active
 * @property int $category_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category $category
 * @method static \Illuminate\Database\Eloquent\Builder|Dial newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dial newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dial query()
 * @method static \Illuminate\Database\Eloquent\Builder|Dial whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dial whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dial whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dial whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dial whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dial whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dial whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Dial extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'active'
    ];

    public static function getRules()
    {
        return [
            'url' => 'required|url'
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'id');
    }

}