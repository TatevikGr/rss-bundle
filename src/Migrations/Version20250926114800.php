<?php

declare(strict_types=1);

namespace TatevikGr\RssFeedBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250926114800 extends AbstractMigration
{
    private const DEFAULT_PREFIX = 'phplist_';

    public function getDescription(): string
    {
        return 'Create feed, item, and item_data tables if they do not exist (keeping phpList rssfeed plugin schema).';
    }

    public function up(Schema $schema): void
    {
        $feedTable = $this->prefixedTableName('feed');
        $itemTable = $this->prefixedTableName('item');
        $itemDataTable = $this->prefixedTableName('item_data');

        // feed table
        if (!$schema->hasTable($feedTable)) {
            $feed = $schema->createTable($feedTable);
            $feed->addColumn('id', 'integer', ['autoincrement' => true]);
            $feed->addColumn('url', 'text', ['notnull' => true]);
            $feed->addColumn('etag', 'string', ['length' => 100, 'notnull' => true, 'default' => '']);
            $feed->addColumn('lastmodified', 'string', ['length' => 100, 'notnull' => true, 'default' => '']);
            $feed->setPrimaryKey(['id']);
        }

        // item table
        if (!$schema->hasTable($itemTable)) {
            $item = $schema->createTable($itemTable);
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
        if ($schema->hasTable($itemTable) && $schema->hasTable($feedTable)) {
            $item = $schema->getTable($itemTable);
            $hasFk = false;
            foreach ($item->getForeignKeys() as $fk) {
                if ($fk->getLocalColumns() === ['feedid'] && $fk->getForeignTableName() === $feedTable) {
                    $hasFk = true;
                    break;
                }
            }
            if (!$hasFk) {
                $item->addForeignKeyConstraint(
                    $feedTable,
                    ['feedid'],
                    ['id'],
                    ['onDelete' => 'RESTRICT', 'onUpdate' => 'NO ACTION'],
                    'FK_item_feed'
                );
            }
        }

        // item_data table
        if (!$schema->hasTable($itemDataTable)) {
            $itemData = $schema->createTable($itemDataTable);
            $itemData->addColumn('itemid', 'integer', ['notnull' => true]);
            $itemData->addColumn('property', 'string', ['length' => 100, 'notnull' => true]);
            $itemData->addColumn('value', 'text', ['notnull' => false]);
            $itemData->setPrimaryKey(['itemid', 'property']);
        }

        // Ensure FK from item_data.itemid -> item.id if both exist and FK not present
        if ($schema->hasTable($itemDataTable) && $schema->hasTable($itemTable)) {
            $itemData = $schema->getTable($itemDataTable);
            $hasFk = false;
            foreach ($itemData->getForeignKeys() as $fk) {
                if ($fk->getLocalColumns() === ['itemid'] && $fk->getForeignTableName() === $itemTable) {
                    $hasFk = true;
                    break;
                }
            }
            if (!$hasFk) {
                $itemData->addForeignKeyConstraint(
                    $itemTable,
                    ['itemid'],
                    ['id'],
                    ['onDelete' => 'CASCADE', 'onUpdate' => 'NO ACTION'],
                    'FK_itemdata_item'
                );
            }
        }
    }

    public function down(Schema $schema): void
    {
        $itemDataTable = $this->prefixedTableName('item_data');
        $itemTable = $this->prefixedTableName('item');
        $feedTable = $this->prefixedTableName('feed');

        // Drop in reverse order to satisfy FKs
        if ($schema->hasTable($itemDataTable)) {
            $schema->dropTable($itemDataTable);
        }
        if ($schema->hasTable($itemTable)) {
            $schema->dropTable($itemTable);
        }
        if ($schema->hasTable($feedTable)) {
            $schema->dropTable($feedTable);
        }
    }

    /**
     * The host application (phpList Core) prefixes all of its tables via DATABASE_PREFIX, defaulting to
     * "phplist_". Migrations run outside the DI container, so that convention is read from the environment
     * directly rather than injected, matching PhpList\Core\Migrations\AbstractPrefixedMigration.
     */
    private function prefixedTableName(string $tableName): string
    {
        $prefix = $_ENV['DATABASE_PREFIX'] ?? getenv('DATABASE_PREFIX');
        $prefix = is_string($prefix) && $prefix !== '' ? $prefix : self::DEFAULT_PREFIX;

        return $prefix . $tableName;
    }
}