<?php

namespace AwStudio\Indexer;

use AwStudio\Indexer\Contracts\HtmlLoader;

class FileContentHtmlLoader implements HtmlLoader
{
    /**
     * Load the URLs html.
     *
     * @param string $url
     * @return string
     */
    public function load($url)
    {
        return file_get_contents($url);
    }
}
