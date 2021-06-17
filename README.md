# Laravel-Indexer

This package allows you to scrape your entire website and create a searchable index of it.
Laravel-Indexer will scan your site's internal links recursivly and save all content to an index table.
The contents of this table are then easily searchable and you can build custom full-text-search.

## Install

Install the package via composer:

```shell
composer require aw-studio/laravel-indexer
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

Once an index is created your can perfom a search on the `WebPage` model:

```php
use AwStudio\Indexer\Models\WebPage;

$results = WebPage::search($request->search)->take(10)->get();
```
