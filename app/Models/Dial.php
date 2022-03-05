<?php

namespace App\Models;

use DiDom\Document;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperDial
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

    /**
     * @throws \DiDom\Exceptions\InvalidSelectorException
     */
    public function updateUrlInfo(string $url): bool
    {
        $document = new Document($url, true);
        $title = $document->first('title')->text();
        $description = (string)$document->first('meta[name=description]')->getAttribute('content');

        $this->description = $description;
        $this->title = $title;

        return $this->save();
    }

}