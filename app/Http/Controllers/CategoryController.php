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
        $user->category()->create($request->all());
        $category = Category::latest()->firstOrFail();

        return Response::json([], 201)->withHeaders([
            'Location' => 'category/' . $category->id
        ]);
    }

    public function show($category): CategoryResource
    {
        $category = Category::whereId($category)->firstOrFail();

        return CategoryResource::make($category);
    }

    public function update(UpdateCategoryRequest $request, $category): CategoryResource
    {
        $category = Category::whereId($category)->firstOrFail();
        $category->update($request->all());

        return CategoryResource::make($category);
    }

    public function delete($category): JsonResponse
    {
        $category = Category::whereId($category)->firstOrFail();
        $category->delete();

        return Response::json([], 204);
    }
}
