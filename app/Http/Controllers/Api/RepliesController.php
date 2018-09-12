<?php

namespace App\Http\Controllers\Api;

use App\Models\Topic;
use App\Transformers\ReplyTransformer;
use App\Http\Requests\Api\ReplyRequest;

class RepliesController extends Controller
{
    public function store(ReplyRequest $request, Topic $topic)
    {
        $reply = $topic->replies()->create([
            'content' => $request->content,
            'user_id' => $this->user()->id,
        ]);

        return $this->response->item($reply, new ReplyTransformer)->setStatusCode(201);
    }
}
