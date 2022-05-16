<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Query\JoinClause;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'img_source'
    ];

    public function morph_image(): MorphTo
    {
        return $this->morphTo();
    }

    public static function imageJoin(string $model, string $table, string $column = null, string $operator = null, int $days = null)
    {
        $date = Carbon::now()->addDays($days)?->format('Y-m-d H:i:s');
        $query = $model::with('images')->select("$table.*");
        if(isset($date, $column, $operator)){
            $query->where("$table.$column", "$operator", "'$date'");
        }

        $query->leftJoin('images', function (JoinClause $join) use ($model, $table) {
            $join->on('images.imageble_id', '=', "$table.id");
            $join->where('images.imageble_type', '=', "$model");
        })->addSelect('images.img_source as images');

        return $query;
    }
}
