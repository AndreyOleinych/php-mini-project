<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\Like;
use App\Models\Tweet;
use App\Models\Comment;

final class LikeRepository
{
    public function save(Like $like): Like
    {
        $like->save();

        return $like;
    }

    public function existsForTweetByUser(int $tweetId, int $userId): bool
    {
        return Like::where([
            'likeable_id' => $tweetId,
            'likeable_type' => Tweet::class,
            'user_id' => $userId
        ])->exists();
    }

    public function deleteForTweetByUser(int $tweetId, int $userId): void
    {
        Like::where([
            'likeable_id' => $tweetId,
            'likeable_type' => Tweet::class,
            'user_id' => $userId
        ])->delete();
    }

    public function existsForCommentByUser(int $commentId, int $userId): bool
    {
        return Like::where([
            'likeable_id' => $commentId,
            'likeable_type' => Comment::class,
            'user_id' => $userId
        ])->exists();
    }

    public function deleteForCommentByUser(int $commentId, int $userId): void
    {
        Like::where([
            'likeable_id' => $commentId,
            'likeable_type' => Comment::class,
            'user_id' => $userId
        ])->delete();
    }
}
