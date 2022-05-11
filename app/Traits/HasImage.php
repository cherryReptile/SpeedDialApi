<?php

namespace App\Traits;

use App\Models\Image;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasImage
{
    public function images(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageble');
}
}