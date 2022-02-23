<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDialRequest;
use App\Http\Requests\UpdateDialRequest;
use App\Http\Resources\DialResource;
use App\Models\Category;
use App\Models\Dial;
use DiDom\Document;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Response;

class SpeedDialController extends Controller
{
    public function create($category, CreateDialRequest $request): JsonResponse
    {
        $category = Category::whereId($category)->firstOrFail();
        $document = new Document($request->post('doc'), true);
        $title = $document->first('title')->text();
        $description = (string)$document->first('meta[name=description]')->getAttribute('content');
        $category->dial()->create([
            'title' => $title,
            'description' => $description,
            'active' => true
        ]);

        return Response::json([], 201)->withHeaders([
            'Location' => 'category/' . $category->id
        ]);
    }

    public function show($dial): DialResource
    {
        $dial = Dial::whereId($dial)->firstOrFail();

        return DialResource::make($dial);
    }

    public function update($dial, UpdateDialRequest $request): DialResource
    {
        $dial = Dial::whereId($dial)->firstOrFail();
        $dial->update($request->all());

        return DialResource::make($dial);
    }

    public function delete(Dial $dial): JsonResponse
    {
        $dial = Dial::whereId($dial)->firstOrFail();
        $dial->delete();

        return Response::json([], 204);
    }
}
