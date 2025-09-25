<?php

declare(strict_types=1);

namespace TatevikGr\RssBundle\RssFeedBundle\Messenger;

use TatevikGr\RssBundle\RssFeedBundle\Service\FeedImporter;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class FetchFeedHandler
{
    public function __construct(private readonly FeedImporter $importer) {}

    public function __invoke(FetchFeedMessage $message): void
    {
        $this->importer->importFeed($message->feedId);
    }
}
