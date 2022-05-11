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
    public function create($id, CreateDialRequest $request): JsonResponse
    {
        $category = \Auth::user()
            ->category()
            ->where('id', '=', $id)
            ->first();

        if (!$category) {
            abort(404);
        }

        /** @var Dial $dial */
        $dial = $category->dial()->create([
            'url' => $request->post('url'),
            'img_source' => '',
            'title' => '',
            'description' => '',
            'active' => true
        ]);

        $dial->updateUrlInfo($request->post('url'));

        return Response::json(DialResource::make($dial), 201);
    }

    public function show($id): DialResource
    {
        $dial = \Auth::user()->dialThroughUser()->where('dials.id', '=', $id)->firstOrFail();

        return DialResource::make($dial);
    }

    public function all()
    {
        return DialResource::collection(\Auth::user()->dialThroughUser);
    }

    /**
     * @throws \DiDom\Exceptions\InvalidSelectorException
     */
    public function update($id, UpdateDialRequest $request): DialResource
    {
        /** @var Dial $dial */
        $dial = \Auth::user()
            ->dialThroughUser()
            ->where('dials.id', '=', $id)
            ->firstOrFail();

        if (file_exists($dial->images()->firstOrFail()->img_source) != true) {
            $dial->images()->firstOrFail()->img_source = null;
        }

        if ($request->post('title') || $request->post('description')) {
            $dial->updateTitleOrDescription($request->post('title', ''), $request->post('description', ''));

            return DialResource::make($dial);
        }

        $dial->updateUrlInfo($request->post('url'));

        return DialResource::make($dial);
    }

    public function delete($id): JsonResponse
    {
        $rows = \Auth::user()->dialThroughUser()->where('dials.id', '=', $id)
            ->delete();

        if ($rows === 0) {
            return Response::json([], 404);
        }

        $img_path = Dial::RESOURCE_PATH . "$id.png";

        if (file_exists($img_path)) {
            unlink($img_path);
        }

        return Response::json([], 204);
    }
}
