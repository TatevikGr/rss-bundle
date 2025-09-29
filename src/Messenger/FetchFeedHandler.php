<?php

declare(strict_types=1);

namespace TatevikGr\RssFeedBundle\Messenger;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TatevikGr\RssFeedBundle\Service\FeedImporter;

#[AsMessageHandler]
class FetchFeedHandler
{
    public function __construct(private readonly FeedImporter $importer) {}

    public function __invoke(FetchFeedMessage $message): void
    {
        $this->importer->importFeed($message->feedId);
    }
}
