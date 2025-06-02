<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    use ApiResponse, AuthorizesRequests;

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $this->authorize('create', Comment::class);
        $validator = \Validator::make($request->all(), [
            'comment_id' => ['nullable', 'integer', 'exists:comments,id'],
            'post_id' => ['required', 'integer', 'exists:posts,id'],
            'content' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator);
        }

        $data = $validator->validated();

        Comment::create([
            'user_id' => auth()->user()->id,
            'approved' => true,
            ...$data
        ]);

        return $this->sendResponse([], 'Comment created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        //
        $this->authorize('update', $comment);
        $validator = \Validator::make($request->all(), [
            'content' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator);
        }

        $data = $validator->validated();

        $comment->update($data);
        return $this->sendResponse([], 'Comment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        //
        $this->authorize('delete', $comment);
        $comment->delete();
        return $this->sendResponse([], 'Comment deleted successfully.');
    }
}
