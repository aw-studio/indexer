# Indexer

This package allows you to scrape your entire website and create a searchable index of it.
Indexer will scan your site's internal links recursivly and save all content to an index table.
The contents of this table are then easily searchable and you can build custom full-text-search.

## Install

Install the package via composer:

```shell
composer require aw-studio/indexer
```

Publish the migration and config files:

```shell
php artisan vendor:publish --tag=indexer
```

Create the database-table:

```shell
php artisan migrate
```

## Config

You can configure the packe in the `config/indexer.php`.

## Usage

You can create an index of your website with the following command:

```shell
php artisan indexer:run
```

Once an index is created your can perfom a search

```php
use AwStudio\Indexer\Indexer;

$results = Indexer::search('apple');

// [
//   {
//     "url": "https://www.my-site.com/fruits",
//     "lang": "en",
//     "title": "Fruits are awesome",
//     "tag": "p",
//     "content": "We really like mangos, oranges and pineapples."
//   },
//   {
//     "url": "https://www.my-site.com/tech",
//     "lang": "en",
//     "title": "The new MacBook",
//     "tag": "p",
//     "content": "Apple is about to release a new MacBook Pro."
//   },
// ]
```

The indexing command will always create a new index and purge the page_index table.
