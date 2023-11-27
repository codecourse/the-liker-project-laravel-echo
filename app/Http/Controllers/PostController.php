<?php

namespace App\Http\Controllers;

use App\Post;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Events\PostWasCreated;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request, Post $post)
    {
        return $post->with(['user'])->latestFirst()->get();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'body' => 'required'
        ]);

        $post = $request->user()->posts()->create([
            'body' => $request->body
        ]);

        broadcast(new PostWasCreated($post))->toOthers();

        return $post->load(['user']);
    }
}



