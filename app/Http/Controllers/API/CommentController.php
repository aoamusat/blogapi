<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends BaseController
{
    /**
    * Display a listing of the resource.
    */
    public function index(Request $request, int $postId)
    {
        $perPage = $request->get('per_page', 10); // Number of items per page
        $comments = Comment::where('post_id', $postId)
            ->with('user')
            ->paginate($perPage);

        return $this->sendResponse($comments, "Comments", 200);
    }
}
