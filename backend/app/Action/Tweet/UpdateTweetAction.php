<?php

declare(strict_types=1);

namespace App\Action\Tweet;

use App\Exceptions\TweetNotFoundException;
use App\Repository\TweetRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;

final class UpdateTweetAction
{
    public function __construct(private TweetRepository $tweetRepository)
    {
    }

    public function execute(UpdateTweetRequest $request): UpdateTweetResponse
    {
        try {
            $tweet = $this->tweetRepository->getById($request->getId());
        } catch (ModelNotFoundException) {
            throw new TweetNotFoundException();
        }

        if ($tweet->author_id !== Auth::id()) {
            throw new AuthorizationException();
        }

        $tweet->text = $request->getText() ?: $tweet->text;

        $tweet = $this->tweetRepository->save($tweet);

        return new UpdateTweetResponse($tweet);
    }
}
