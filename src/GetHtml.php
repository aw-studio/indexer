<?php

namespace AwStudio\Indexer;

use AwStudio\Indexer\Contracts\UrlParser;

class GetHtml implements UrlParser
{
    /**
     * Get the URLs html.
     *
     * @return void
     */
    public function getHtml(string $url): string
    {
        return file_get_contents($url);
    }
}
