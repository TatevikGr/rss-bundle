<?php

declare(strict_types=1);

namespace TatevikGr\RssBundle\RssFeedBundle\Messenger;

class FetchFeedMessage
{
    public function __construct(public readonly int $feedId) {}
}
