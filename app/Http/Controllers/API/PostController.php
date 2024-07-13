<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PostController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);
            $posts = Post::paginate($perPage);
            return response()->json(['posts' => $posts]);
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => "Internal server error"], 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'title' => 'required|string|max:255',
                    'content' => 'required|string',
                ]
            );

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors(), 400);
            }

            $post = new Post();
            $post->title = $request->title;
            $post->content = $request->content;
            $post->user_id = $request->user()->id;
            $post->save();

            return $this->sendResponse($post, "Post created successfully", 201);
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => "Internal server error"], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $post = Post::find($id);
            if (!$post) {
                return $this->sendError("Post not found", [], 404);
            }
            $comments = $post->comments();
            return $this->sendResponse(["post" => $post, 'comments' => $comments], "Success");
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => "Internal server error"]);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $post = Post::find($id);
            if (!$post) {
                return $this->sendError("Post not found", [], 404);
            }
            $validator = Validator::make(
                $request->all(),
                [
                    'title' => 'required|string|max:255',
                    'content' => 'required|string',
                ]
            );

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors(), 400);
            }

            $post->title = $request->title;
            $post->content = $request->content;
            $post->save();

            return $this->sendResponse($post, "Post updated successfully", 201);
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => "Internal server error"]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $post = Post::find($id);
            if (!$post) {
                return $this->sendError("Post not found", [], 404);
            }
            $post->delete();
            return $this->sendResponse([], "Post deleted successfully", 200);
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => "Internal server error"]);
        }
    }
}
