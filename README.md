# Indexer

## Install

```shell
composer require aw-studio/indexer
```

```shell
php artisan vendor:publish --tag=indexer
```

```shell
php artisan migrate
```

## Config

TODO.

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
