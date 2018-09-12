<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Reply;
use App\Models\Topic;
use App\Transformers\ReplyTransformer;
use App\Http\Requests\Api\ReplyRequest;

class RepliesController extends Controller
{
    public function index(Topic $topic)
    {
        $replies = $topic->replies()->paginate(20);

        return $this->response->paginator($replies, new ReplyTransformer);
    }

    public function userIndex(User $user)
    {
        $replies = $user->replies()->paginate(20);

        return $this->response->paginator($replies, new ReplyTransformer);
    }


    public function store(ReplyRequest $request, Topic $topic)
    {
        $reply = $topic->replies()->create([
            'content' => $request->content,
            'user_id' => $this->user()->id,
        ]);

        return $this->response->item($reply, new ReplyTransformer)->setStatusCode(201);
    }

    public function destroy(Topic $topic, Reply $reply)
    {
        if ($reply->topic->id != $topic->id) {
            return $this->response->errorBadRequest();
        }

        $this->authorize('destroy', $reply);
        $reply->delete();

        return $this->response->noContent();
    }
}
