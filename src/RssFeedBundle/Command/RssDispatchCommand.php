<?php

declare(strict_types=1);

namespace TatevikGr\RssBundle\RssFeedBundle\Command;

use TatevikGr\RssBundle\RssFeedBundle\Messenger\FetchFeedMessage;
use TatevikGr\RssBundle\RssFeedBundle\Repository\FeedRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(name: 'rss:dispatch', description: 'Dispatch feed imports for all configured feeds')]
class RssDispatchCommand extends Command
{
    public function __construct(
        private readonly FeedRepository $feedRepository,
        private readonly MessageBusInterface $bus
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $feeds = $this->feedRepository->findAll();
        foreach ($feeds as $feed) {
            $this->bus->dispatch(new FetchFeedMessage($feed->getId()));
        }
        $output->writeln(sprintf('Dispatched %d feed(s)', count($feeds)));

        return Command::SUCCESS;
    }
}
