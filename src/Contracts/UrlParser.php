<?php

namespace AwStudio\Indexer\Contracts;

interface UrlParser
{
    /**
     * Get the URLs html.
     *
     * @param string $url
     * @return string
     */
    public function getHtml(string $url);
}
