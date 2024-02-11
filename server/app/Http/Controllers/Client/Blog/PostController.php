<?php

namespace App\Http\Controllers\Client\Blog;

use App\Http\Controllers\Controller;
use App\Http\Resources\Client\Blog\PostResource;
use App\Models\Blog\Post;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\EmailService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function list(Request $request): JsonResponse
    {
        $query = Post::query()
            ->active();

        if ($request->has('status')) {
            $query->where('status', '=', $request->get('status'));
        }

        $posts = $query->get();
        return Response::json([
            'success' => true,
            'data' => PostResource::collection($posts)
        ]);
    }

    public function detail(Request $request, int $id): JsonResponse
    {
        if (!$id) {
            return Response::json([
                'success' => false,
                'errors' => [
                    'ID parameter is missing'
                ]
            ], 400);
        }

        $post = Post::query()->find($id);

        if (!$post) {
            return Response::json([
                'success' => false,
                'errors' => [
                    'Post was not found'
                ]
            ], 404);
        }

        return Response::json([
            'success' => true,
            'data' => PostResource::make($post)
        ]);
    }

    public function create(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'author' => 'required',
            'body' => 'required'
        ]);

        if ($validator->fails()) {
            return Response::json([
                'success' => false,
                'errors' => [$validator->messages()]
            ], 400);
        }

        $post = new Post();
        $post->slug = Str::slug($request->title);
        $post->fill($request->all());
        $post->save();

        return Response::json([
            'success' => true,
            'data' => PostResource::make($post)
        ]);
    }
}
