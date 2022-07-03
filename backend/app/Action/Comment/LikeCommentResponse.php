<?php

declare(strict_types=1);

namespace App\Action\Comment;

final class LikeCommentResponse
{
    public function __construct(private string $status)
    {
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
