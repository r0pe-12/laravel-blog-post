<?php

namespace App\Http\Resources\Posts;

use App\Http\Resources\Categories\CategoryResource;
use App\Http\Resources\CommentResource;
use App\Http\Resources\MediaResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => new UserResource($this->user),
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'cover' => $this->image,
            'comments' => CommentResource::collection($this->comments()->withTrashed()->whereNull('comment_id')->get()),
            'categories' => CategoryResource::collection($this->categories),
            'media' => MediaResource::collection($this->media)
        ];
    }
}
