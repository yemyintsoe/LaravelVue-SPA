<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if($request->q !== null) {
            $categories = Category::where('name', 'ilike', '%'.$request->q.'%')
                                ->latest()
                                ->paginate(10);
            return CategoryResource::collection($categories);
        }
        if($request->type === 'all') {
            return CategoryResource::collection(Category::all());
        }
        return CategoryResource::collection(Category::withTrashed()->latest()->paginate(10));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories,name'
        ]);

        if($validator->fails()) {
            return response()->json($validator->messages()->add('status', 'fails'));
        }
        $data = Category::create($validator->validated());
        return response()->json(['status' => 'passes', 'data' => $data]);
    }

    public function show(string $id)
    {
        return new CategoryResource(Category::findOrFail($id));
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories,name,'.$id
        ]);

        if($validator->fails()) {
            return response()->json($validator->messages()->add('status', 'fails'));
        }
        Category::findOrFail($id)->update($validator->validated());
        return response()->json(['status' => 'passes']);
    }

    public function destroy(string $id)
    {
        Category::findOrFail($id)->forceDelete();
        return response()->json(['status' =>'passes']);
    }
}
