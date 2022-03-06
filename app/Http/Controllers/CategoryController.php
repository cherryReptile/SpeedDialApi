<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PhpParser\ErrorHandler\Collecting;
use Response;

class CategoryController extends Controller
{
    public function create(CreateCategoryRequest $request): JsonResponse
    {
        $user = \Auth::user();
        $category = $user->category()->create($request->all());

        return Response::json([], $category instanceof Category ? 201 : 500);
    }

    public function show($id): CategoryResource
    {
        $category = \Auth::user()->category()->whereId($id)->firstOrFail();

        return CategoryResource::make($category);
    }

    public function all()
    {
        return CategoryResource::collection(\Auth::user()->category);
    }

    public function update($id, UpdateCategoryRequest $request): CategoryResource
    {
        $category = \Auth::user()->category()->whereId($id)->firstOrFail();
        $category->update($request->all());

        return CategoryResource::make($category);
    }

    public function delete($id): JsonResponse
    {
        $rows = \Auth::user()->category()->whereId($id)->delete();

        if ($rows === 0) {
            return Response::json([], 404);
        }

        return Response::json([], 204);
    }
}
