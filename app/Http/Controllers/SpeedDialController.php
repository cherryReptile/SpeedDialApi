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
use function PHPUnit\Framework\isNull;

class SpeedDialController extends Controller
{
    /**
     * @throws \DiDom\Exceptions\InvalidSelectorException
     */
    public function create($category, CreateDialRequest $request): JsonResponse
    {
        $category = \Auth::user()
            ->category()
            ->where('id', '=', $category)
            ->first();

        if (!$category) {
            abort(404);
        }

        /** @var Dial $dial */
        $dial = $category->dial()->create([
            'url' => $request->post('url'),
            'title' => '',
            'description' => '',
            'active' => true
        ]);

        $dial->updateUrlInfo($request->post('url'));

        return Response::json([], 201);
    }

    public function show($dial): DialResource
    {
        $dial = \Auth::user()->dialThroughUser()->where('dials.id', '=', $dial)->firstOrFail();

        return DialResource::make($dial);
    }

    public function all()
    {
        return DialResource::collection(\Auth::user()->dialThroughUser);
    }

    /**
     * @throws \DiDom\Exceptions\InvalidSelectorException
     */
    public function update($dial, UpdateDialRequest $request): DialResource
    {
        /** @var Dial $dial */
        $dial = \Auth::user()
            ->dialThroughUser()
            ->where('dials.id', '=', $dial)
            ->firstOrFail();

        $dial->updateUrlInfo($request->post('url'));

        return DialResource::make($dial);
    }

    public function delete($dial): JsonResponse
    {
        $rows = \Auth::user()->dialThroughUser()->where('dials.id', '=', $dial)
            ->delete();

        if ($rows === 0) {
            return Response::json([], 404);
        }

        return Response::json([], 204);
    }
}
