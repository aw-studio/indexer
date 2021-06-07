<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default URL
    |--------------------------------------------------------------------------
    |
    | This option defines the the default URL that should be indexed, when no
    | URL parameter is given.
    |
    */

    'default_url' => null,

    /*
    |--------------------------------------------------------------------------
    | Indexed Tags
    |--------------------------------------------------------------------------
    |
    | This option controls the tags that should be indexed. The order of the
    | tags is also decisive for the sorting of the search results.
    |
    */

    'tags' => [
        'h2',
        'h3',
        'h4',
        'h5',
        'p',
        'li',
    ],

    /*
    |--------------------------------------------------------------------------
    | Title Source
    |--------------------------------------------------------------------------
    |
    | This option controls which tag should be used as a title for a record.
    | A good source for a title might be a page's H1. If No H1 is present
    | the <title> tag might be an alternative.
    |
    */

    'title' => [
        'h1',
        'title',
    ],

    /*
    |--------------------------------------------------------------------------
    | Tags to be removed before indexing
    |--------------------------------------------------------------------------
    |
    | This option controls the tags that should be removed from the entire
    | sourcecode before indexing. Some tags might create noise, such as inline
    | SVG files includimg title tags for example.
    |
    */

    'remove' => [
        'script',
        'svg',
        'nav',
        'footer',
    ],

    /*
    |--------------------------------------------------------------------------
    | Exclude URLs
    |--------------------------------------------------------------------------
    |
    | This option controls URLs that should not be indexed. You can exclude
    | paths including it's sub-paths by adding a * at the end of the url.
    |
    */

    'exclude' => [
        // https://my-site.com/imprint
        // https://my-site.com/secret-projects*
    ],

    /*
    |--------------------------------------------------------------------------
    | WebPage Model
    |--------------------------------------------------------------------------
    |
    | This model is responsible for storing webpage content in the database.
    |
    */

    'model' => AwStudio\Indexer\Models\WebPage::class,

    /*
    |--------------------------------------------------------------------------
    | Webpage Databasetable Name
    |--------------------------------------------------------------------------
    |
    | You may change the database table name of the table that store web page
    | content.
    |
    */

    'table' => 'web_pages',
];
