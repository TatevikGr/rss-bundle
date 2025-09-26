<?php

declare(strict_types=1);

namespace TatevikGr\RssBundle\RssFeedBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250926114800 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create feed, item, and item_data tables if they do not exist (keeping phpList rssfeed plugin schema).';
    }

    public function up(Schema $schema): void
    {
        // feed table
        if (!$schema->hasTable('feed')) {
            $feed = $schema->createTable('feed');
            $feed->addColumn('id', 'integer', ['autoincrement' => true]);
            $feed->addColumn('url', 'text', ['notnull' => true]);
            $feed->addColumn('etag', 'string', ['length' => 100, 'notnull' => true, 'default' => '']);
            $feed->addColumn('lastmodified', 'string', ['length' => 100, 'notnull' => true, 'default' => '']);
            $feed->setPrimaryKey(['id']);
        }

        // item table
        if (!$schema->hasTable('item')) {
            $item = $schema->createTable('item');
            $item->addColumn('id', 'integer', ['autoincrement' => true]);
            $item->addColumn('uid', 'string', ['length' => 100, 'notnull' => true]);
            $item->addColumn('feedid', 'integer', ['notnull' => true]);
            $item->addColumn('published', 'datetime', ['notnull' => true]);
            $item->addColumn('added', 'datetime', ['notnull' => true]);
            $item->setPrimaryKey(['id']);
            $item->addIndex(['feedid', 'published'], 'feedpublishedindex');
            $item->addIndex(['feedid', 'uid'], 'feeduidindex');
        }

        // Ensure FK from item.feedid -> feed.id if both exist and FK not present
        if ($schema->hasTable('item') && $schema->hasTable('feed')) {
            $item = $schema->getTable('item');
            $hasFk = false;
            foreach ($item->getForeignKeys() as $fk) {
                if ($fk->getLocalColumns() === ['feedid'] && $fk->getForeignTableName() === 'feed') {
                    $hasFk = true;
                    break;
                }
            }
            if (!$hasFk) {
                $item->addForeignKeyConstraint('feed', ['feedid'], ['id'], ['onDelete' => 'RESTRICT', 'onUpdate' => 'NO ACTION'], 'FK_item_feed');
            }
        }

        // item_data table
        if (!$schema->hasTable('item_data')) {
            $itemData = $schema->createTable('item_data');
            $itemData->addColumn('itemid', 'integer', ['notnull' => true]);
            $itemData->addColumn('property', 'string', ['length' => 100, 'notnull' => true]);
            $itemData->addColumn('value', 'text', ['notnull' => false]);
            $itemData->setPrimaryKey(['itemid', 'property']);
        }

        // Ensure FK from item_data.itemid -> item.id if both exist and FK not present
        if ($schema->hasTable('item_data') && $schema->hasTable('item')) {
            $itemData = $schema->getTable('item_data');
            $hasFk = false;
            foreach ($itemData->getForeignKeys() as $fk) {
                if ($fk->getLocalColumns() === ['itemid'] && $fk->getForeignTableName() === 'item') {
                    $hasFk = true;
                    break;
                }
            }
            if (!$hasFk) {
                $itemData->addForeignKeyConstraint('item', ['itemid'], ['id'], ['onDelete' => 'CASCADE', 'onUpdate' => 'NO ACTION'], 'FK_itemdata_item');
            }
        }
    }

    public function down(Schema $schema): void
    {
        // Drop in reverse order to satisfy FKs
        if ($schema->hasTable('item_data')) {
            $schema->dropTable('item_data');
        }
        if ($schema->hasTable('item')) {
            $schema->dropTable('item');
        }
        if ($schema->hasTable('feed')) {
            $schema->dropTable('feed');
        }
    }
}
