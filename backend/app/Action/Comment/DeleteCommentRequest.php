<?php

declare(strict_types=1);

namespace App\Action\Comment;

final class DeleteCommentRequest
{
    public function __construct(private int $id)
    {
    }

    public function getId(): int
    {
        return $this->id;
    }
}
