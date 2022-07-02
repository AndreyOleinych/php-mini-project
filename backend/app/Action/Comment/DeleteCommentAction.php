<?php

declare(strict_types=1);

namespace App\Action\Comment;

use App\Exceptions\CommentNotFoundException;
use App\Repository\CommentRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;

final class DeleteCommentAction{
    public function __construct(private CommentRepository $commentRepository)
    {
    }

    public function execute(DeleteCommentRequest $request): void
    {
        try {
            $comment = $this->commentRepository->getById($request->getId());
        } catch (ModelNotFoundException) {
            throw new CommentNotFoundException();
        }

        if ($comment->author_id !== Auth::id()) {
            throw new AuthorizationException();
        }

        $this->commentRepository->delete($comment);
    }
}
