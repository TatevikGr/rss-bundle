<?php

declare(strict_types=1);

namespace TatevikGr\RssBundle\RssFeedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Tatevikgr\RssBundle\RssFeedBundle\Repository\ItemDataRepository;

#[ORM\Entity(repositoryClass: ItemDataRepository::class)]
#[ORM\Table(name: 'item_data')]
class ItemData
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: FeedItem::class)]
    #[ORM\JoinColumn(name: 'itemid', referencedColumnName: 'id', nullable: false)]
    private FeedItem $item;

    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 100)]
    private string $property;

    #[ORM\Column(type: 'text', name: 'value')]
    private ?string $value = null;

    public function getItem(): FeedItem { return $this->item; }
    public function setItem(FeedItem $item): void { $this->item = $item; }
    public function getProperty(): string { return $this->property; }
    public function setProperty(string $property): void { $this->property = $property; }
    public function getValue(): ?string { return $this->value; }
    public function setValue(?string $value): void { $this->value = $value; }
}
