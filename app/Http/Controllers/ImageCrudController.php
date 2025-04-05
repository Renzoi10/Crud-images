<?php

namespace App\Http\Controllers;

use App\Models\image_crud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ImageCrudController extends Controller
{

    public function addArticle(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:10|max:100',
            'image_route' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'content' => 'required|string|min:10|max:100',
            'author' => 'required|string|min:10|max:100',
            'labels' => 'required|string|min:10|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $rutaimg = $request->file('image_route')->store('myimgs');
        $image = new image_crud();
        $image->title = $request->title;
        $image->image_route = $rutaimg;
        $image->content = $request->content;
        $image->author = $request->author;
        $image->labels = $request->labels;
        $image->save();

        return response()->json(['message' => 'Imagen agregada con exito'], 200);
    }

    public function getArticles()
    {
        $image = image_crud::all();

        if ($image->isEmpty()) {
            return response()->json(['message' => 'No hay articulos'], 404);
        }

        foreach ($image as $img) {
            $img->image_route = asset(Storage::url($img->image_route));
        }

        return response()->json($image, 200);
    }

    public function getArticlesById($id)
    {
        $images = image_crud::find($id);
        if (!$images) {
            return response()->json(['message' => 'No hay articulos'], 404);
        }
        $images->image_route = asset(Storage::url($images->image_route));
        return response()->json($images, 200);
    }

    public function updateArticle(Request $request, $id)
    {
        $image = image_crud::find($id);
        if (!$image) {
            return response()->json(['message' => 'No hay articulos'], 404);
        }
        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|min:10|max:100',
            'image_route' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'content' => 'nullable|string|min:10|max:100',
            'author' => 'nullable|string|min:10|max:100',
            'labels' => 'nullable|string|min:10|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        if ($request->has('title')) {
            $image->title = $request->title;
        }
        if ($request->has('image_route')) {
            if ($image->image_route  && Storage::exists($image->image_route)) {
                Storage::delete($image->image_route);
            }
            $rutaimg = $request->file('image_route')->store('myimgs');
            $image->image_route = $rutaimg;
        }
        if ($request->has('content')) {
            $image->content = $request->content;
        }
        if ($request->has('author')) {
            $image->author = $request->author;
        }
        if ($request->has('labels')) {
            $image->labels = $request->labels;
        }
        $image->update();
        return response()->json(['message' => 'Imagen actualizada con exito'], 200);
    }

    public function deleteArticle($id)
    {
        $image = image_crud::find($id);
        if (!$image) {
            return response()->json(['message' => 'No hay articulos'], 404);
        }
        if ($image->image_route  && Storage::exists($image->image_route)) {
            Storage::delete($image->image_route);
        }
        $image->delete();
        return response()->json(['message' => 'Imagen eliminada con exito'], 200);
    }
}
