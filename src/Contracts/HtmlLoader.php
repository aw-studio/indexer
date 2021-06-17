<?php

namespace AwStudio\Indexer\Contracts;

interface HtmlLoader
{
    /**
     * Get the URLs html.
     *
     * @param string $url
     * @return string
     */
    public function load($url);
}
