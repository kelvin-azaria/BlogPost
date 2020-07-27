<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComment;
use App\User;

class UserCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['store']);
    }

    public function store(User $user, StoreComment $request)
    {
        // Comment::create()
        $user->CommentsOn()->create([
            'content' => $request->input('content'),
            'user_id' => $request->user()->id
        ]);

        $request->session()->flash('status', 'Comment was created!');

        return redirect()
            ->back()
            ->withStatus('Comment was created!');
    }
}
