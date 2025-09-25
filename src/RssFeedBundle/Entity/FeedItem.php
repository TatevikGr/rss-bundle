<?php

declare(strict_types=1);

namespace TatevikGr\RssBundle\RssFeedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Tatevikgr\RssBundle\RssFeedBundle\Repository\FeedItemRepository;

#[ORM\Entity(repositoryClass: FeedItemRepository::class)]
#[ORM\Table(name: 'item')]
#[ORM\Index(columns: ['feedid', 'published'], name: 'feedpublishedindex')]
#[ORM\Index(columns: ['feedid', 'uid'], name: 'feeduidindex')]
class FeedItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100)]
    private string $uid = '';

    #[ORM\ManyToOne(targetEntity: Feed::class)]
    #[ORM\JoinColumn(name: 'feedid', referencedColumnName: 'id', nullable: false)]
    private Feed $feed;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $published;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $added;

    public function getId(): ?int { return $this->id; }
    public function getUid(): string { return $this->uid; }
    public function setUid(string $uid): void { $this->uid = $uid; }
    public function getFeed(): Feed { return $this->feed; }
    public function setFeed(Feed $feed): void { $this->feed = $feed; }
    public function getPublished(): \DateTimeInterface { return $this->published; }
    public function setPublished(\DateTimeInterface $published): void { $this->published = $published; }
    public function getAdded(): \DateTimeInterface { return $this->added; }
    public function setAdded(\DateTimeInterface $added): void { $this->added = $added; }
}
