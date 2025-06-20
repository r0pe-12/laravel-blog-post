<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user' => new UserResource($this->user),
            'id' => $this->id,
            'content' => $this->content,
            'approved' => $this->approved,
            'deleted' => !($this->deleted_at == null),
            'children' => CommentResource::collection($this->children)
        ];
    }
}
