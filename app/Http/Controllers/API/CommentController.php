<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Comment;
use Illuminate\Support\Facades\Validator;

class CommentController extends BaseController
{
    /**
    * Display a listing of the resource.
    */
    public function getComments(Request $request, int $postId)
    {
        try {
            $perPage = $request->get('per_page', 10); // Number of items per page
            $comments = Comment::where('post_id', $postId)
                ->with('user')
                ->paginate($perPage);

            return $this->sendResponse($comments, "Comments", 200);
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => "Internal server error"]);
        }
    }

    public function createComment(Request $request, int $postId)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'body' => 'required|string',
                ]
            );

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors(), 400);
            }

            $comment = Comment::create([
                'user_id' => Auth::id(),
                'post_id' => $postId,
                'body' => $request->body,
            ]);

            return response()->json($comment, 201);
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => "Internal server error"]);
        }
    }
}
