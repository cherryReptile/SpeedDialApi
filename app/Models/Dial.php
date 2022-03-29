<?php

namespace App\Models;

use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dial extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'img_source',
        'title',
        'description',
        'active'
    ];

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
            $img_source = shell_exec("cd /var/www/resources/js/node; node index.js $url {$this->id}");
        } catch (\Exception $exception) {
        }

        $this->url = $url;
        $this->img_source = $img_source;
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