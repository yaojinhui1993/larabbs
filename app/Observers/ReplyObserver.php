<?php

namespace App\Observers;

use App\Models\Reply;
use App\Notifications\TopicReplied;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver
{
    public function saving(Reply $reply)
    {
        $reply->content = clean($reply->content, 'user_topic_body');
    }

    public function saved(Reply $reply)
    {
        $reply->topic->increment('reply_count', 1);

        $reply->topic->user->notify(new TopicReplied(($reply)));
    }

    public function updating(Reply $reply)
    {
        //
    }
}