<?php

namespace App\Http\Resources\Posts;

use App\Http\Resources\Categories\CategoryResource;
use App\Http\Resources\MediaResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostCollection extends JsonResource
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
            'categories' => CategoryResource::collection($this->categories)
        ];
    }
}
