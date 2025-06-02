<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\Posts\PostCollection;
use App\Http\Resources\Posts\PostResource;
use App\Models\Media;
use App\Models\Post;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class PostController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $validated = $request->validate([
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);
        $posts = Post::all()->where('published', true);
        if ($request->has('user_id')) {
            $posts = $posts->where('user_id', $validated['user_id']);
        }

        return $this->sendResponse(PostCollection::collection($posts));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        //
        $data = $request->validated();
        $post = Post::create([
                ...Arr::except($data, ['categories', 'media'])
            ]
        );
        $post->categories()->attach($data['categories']);
        foreach ($data['media'] as $media) {
            Media::create([
                'post_id' => $post->id,
                'url' => $media
            ]);
        }
        return $this->sendResponse(new PostResource($post));
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
        return $this->sendResponse(new PostResource($post));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $data = $request->validated();
        $post->update(Arr::except($data, ['categories', 'media']));

        $post->categories()->sync($data['categories']);
        $post->media()->delete();
        foreach ($data['media'] as $media) {
            Media::create([
                'post_id' => $post->id,
                'url' => $media
            ]);
        }
        return $this->sendResponse(new PostResource($post));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
        $post->update(['published' => false]);
        return $this->sendResponse([], 'Post deleted successfully.');
    }
}
