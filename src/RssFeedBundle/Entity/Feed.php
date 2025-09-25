<?php

declare(strict_types=1);

namespace TatevikGr\RssBundle\RssFeedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Tatevikgr\RssBundle\RssFeedBundle\Repository\FeedRepository;

#[ORM\Entity(repositoryClass: FeedRepository::class)]
#[ORM\Table(name: 'feed')]
class Feed
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'text')]
    private string $url = '';

    #[ORM\Column(type: 'string', length: 100)]
    private string $etag = '';

    #[ORM\Column(name: 'lastmodified', type: 'string', length: 100)]
    private string $lastModified = '';

    public function getId(): ?int { return $this->id; }
    public function getUrl(): string { return $this->url; }
    public function setUrl(string $url): void { $this->url = $url; }
    public function getEtag(): string { return $this->etag; }
    public function setEtag(string $etag): void { $this->etag = $etag; }
    public function getLastModified(): string { return $this->lastModified; }
    public function setLastModified(string $lastModified): void { $this->lastModified = $lastModified; }
}
