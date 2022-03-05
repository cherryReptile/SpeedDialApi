<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDialRequest;
use App\Http\Requests\UpdateDialRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\DialResource;
use App\Models\Category;
use App\Models\Dial;
use App\Models\User;
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
        $category = \Auth::user()
            ->category()
            ->where('id', '=', $category)
            ->first()
        ;

        if (!$category) {
            abort(403);
        }

        /** @var Dial $dial */
        $dial = $category->dial()->create([
            'title' => '',
            'description' => '',
            'active' => true
        ]);

        $dial->updateUrlInfo($request->post('url'));

        return Response::json([], 201);
    }

    public function show($dial): DialResource
    {
        $dial = Dial::whereId($dial)->firstOrFail();

        return DialResource::make($dial);
    }

    /**
     * @throws \DiDom\Exceptions\InvalidSelectorException
     */
    public function update($dial, UpdateDialRequest $request): DialResource
    {
        /** @var Dial $dial */
        $dial = \Auth::user()->dialThroughUser()->where('id', '=', $dial)
            ->firstOrFail();

        $dial->updateUrlInfo($request->post('url'));

        return DialResource::make($dial);
    }

    public function delete($dial): JsonResponse
    {
        $dial = \Auth::user()->dialThroughUser()->where('id', '=', $dial)
            ->delete();
        //TODO: Проверка на то был ли удален действительно или нет

        return Response::json([], 204);
    }

    public function SpeedDials(Request $request)
    {
        return CategoryResource::collection($request->user()->dialThroughUser);
    }
}
