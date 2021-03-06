<?php

namespace App\Models;

use App\Jobs\InitNodeScriptJob;
use App\Traits\HasImage;
use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dial extends Model
{
    use HasFactory, HasImage;

    protected $fillable = [
        'url',
        'title',
        'description',
        'active'
    ];

    public const RESOURCE_PATH = '/var/www/storage/speeddials/';

    private string $url;

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
        $title = '';
        $description = '';
        $img_source = '';

        try {
            $document = new Document($url, true);
            $title = (string)$document?->first('title')?->text();
            $description = (string)$document?->first('meta[name=description]')?->getAttribute('content');
            dispatch(new InitNodeScriptJob($this->id, $url));
            $img_source = self::RESOURCE_PATH . "$this->id.png";
        } catch (\Exception $exception) {
        }

        $this->url = $url;
        $this->images()->create([
            'img_source' => $img_source
        ]);
        $this->description = $description;
        $this->title = $title;

        return $this->save();
    }

    public function updateTitleOrDescription(string $title = '', string $description = ''): bool
    {
        $this->title = $title;
        $this->description = $description;

        return $this->save();
    }

}