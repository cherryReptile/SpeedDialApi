<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDialRequest;
use App\Http\Requests\UpdateDialRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\DialResource;
use App\Models\Category;
use App\Models\Dial;
use DiDom\Document;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Collection;
use Psy\Util\Json;
use Response;

class SpeedDialController extends Controller
{
    public function create($category, CreateDialRequest $request): JsonResponse
    {
        $category = Category::whereId($category)->firstOrFail();
        $document = new Document($request->post('url'), true);
        $title = $document->first('title')->text();
        $description = (string)$document->first('meta[name=description]')->getAttribute('content');
        $category->dial()->create([
            'title' => $title,
            'description' => $description,
            'active' => true
        ]);
        $dial = Dial::latest()->firstOrFail();

        return Response::json([], 201)->withHeaders([
            'Location' => 'dial/' . $dial->id
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

    public function delete($dial): JsonResponse
    {
        $dial = Dial::whereId($dial)->firstOrFail();
        $dial->delete();

        return Response::json([], 204);
    }

    public function SpeedDials(Request $request): JsonResponse
    {
        $user = $request->user()->id;
        $categories = Category::whereUserId($user);
        $speedDials = CategoryResource::collection($categories->with('dial')->get());

        return Response::json($speedDials);
    }
}
