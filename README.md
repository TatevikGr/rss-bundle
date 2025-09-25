## Symfony Bundle (separate package) ##

This repository includes a reusable Symfony bundle that provides RSS feed functionality while preserving https://github.com/bramley/phplist-plugin-rssfeed database structure.
It targets PHP 8.1 and uses:

- Feed parser: debril/feed-io
- Templating: Twig
- Queue: Symfony Messenger
- Storage: Doctrine ORM (tables: feed, item, item_data)
- Scheduling: plain cron → bin/console rss:dispatch (cron fires a lightweight dispatcher; heavy work runs in Messenger)

Bundle namespace: TatevikGr\\RssFeed\\RssFeedBundle

How to use it inside your Symfony application:

1) Require the dependencies in your app composer.json (this repo already declares them, but they must be installed in your app):
    - symfony/framework-bundle, symfony/messenger, doctrine/orm, twig/twig, debril/feed-io

2) Register the bundle (Symfony Flex usually auto-registers bundles; otherwise, add to config/bundles.php):

   TatevikGr\\RssFeed\\RssFeedBundle::class => ['all' => true],

3) Ensure Doctrine is configured to use your existing database which contains the tables feed, item, item_data.
   The bundle maps entities to those exact tables, preserving the schema.

4) Messenger: configure a transport (e.g. async) and run a worker:
    - symfony console messenger:consume async -vv

5) Schedule the dispatcher with cron (every 5 minutes for example):
    - */5 * * * * /path/to/project/bin/console rss:dispatch --env=prod >> /var/log/rss_dispatch.log 2>&1

6) Rendering: the bundle ships a simple Twig template at @RssFeed/items.html.twig for displaying items. You can render items by fetching them from Doctrine and passing an array with keys: title, content, url, published.

Notes:
- The bundle writes items into the existing tables and de-duplicates by (feedid, uid). It stores title, content, and url in the item_data table, matching the original plugin’s storage approach.
