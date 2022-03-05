<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Response;

class CategoryController extends Controller
{
    public function create(CreateCategoryRequest $request): JsonResponse
    {
        $user = \Auth::user();
        $category = $user->category()->create($request->all());

        return Response::json([], $category instanceof Category ? 201 : 500);
    }

    public function show($category)
    {
        $category = \Auth::user()->category()->whereId($category)->firstOrFail();

        return CategoryResource::make($category);
    }

    public function update(UpdateCategoryRequest $request, $category): CategoryResource
    {
        $category = \Auth::user()->category()->whereId($category)->firstOrFail();
        $category->update($request->all());

        return CategoryResource::make($category);
    }

    public function delete($category): JsonResponse
    {
        $rows = \Auth::user()->category()->whereId($category)->delete();

        if ($rows === 0) {
            return Response::json([], 404);
        }

        return Response::json([], 204);
    }

    public function all()
    {
        return CategoryResource::collection(\Auth::user()->category);
    }
}
