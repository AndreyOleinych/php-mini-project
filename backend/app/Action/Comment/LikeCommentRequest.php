<?php

declare(strict_types=1);

namespace App\Action\Comment;

final class LikeCommentRequest
{
    public function __construct(private int $commentId)
    {
    }

    public function getCommentId(): int
    {
        return $this->commentId;
    }
}
