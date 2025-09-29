<?php

declare(strict_types=1);

namespace TatevikGr\RssFeedBundle\Service;

use FeedIo\FeedIo;
use Psr\Http\Client\ClientInterface;
use Psr\Log\LoggerInterface;

class FeedIoFactory
{
    public function __construct(
        private ClientInterface $httpClient,
        private LoggerInterface $logger
    ) {}

    public function create(): FeedIo
    {
        // FeedIo accepts PSR clients/factories; logger is optional
        return new FeedIo(
            $this->httpClient,
            $this->logger,
        );
    }
}
