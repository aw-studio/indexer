<?php

namespace AwStudio\Indexer\Commands;

use AwStudio\Indexer\Contracts\HtmlLoader;
use DOMDocument;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RunCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'indexer:run {url?} {--once}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a search index of a website.';

    /**
     * Create a new command instance.
     *
     * @param HtmlLoader $loader
     * @return void
     */
    public function __construct(
        protected HtmlLoader $loader,
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $url = $this->argument('url') ?: config('indexer.default_url');

        if (! $url) {
            $this->info('No URL has been set');

            return;
        }

        if ($this->option('once')) {
            $urls [] = $url;
        } else {
            $urls = $this->createSitemap($url);
        }

        DB::table(config('indexer.table'))->truncate();

        $nodes = config('indexer.tags');

        $excludePaths = $this->getExcludedPaths();

        $bar = $this->output->createProgressBar(count($urls));

        $bar->start();

        foreach ($urls as $url) {
            // skip excluded
            if (in_array($url, config('indexer.exclude'))) {
                continue;
            }

            $skip = false;
            foreach ($excludePaths as $excludePath) {
                if (strpos($url, $excludePath) === 0) {
                    $skip = true;
                }
            }
            if ($skip) {
                continue;
            }

            // get content
            $content = $this->loader->load($url);

            foreach (config('indexer.remove') as $tag) {
                $content = $this->removeTag($tag, $content);
            }

            $doc = new DOMDocument();
            libxml_use_internal_errors(true);
            $doc->loadHTML($content);
            libxml_clear_errors();

            foreach ($nodes as $node) {
                foreach ($doc->getElementsByTagName($node) as $paragraph) {
                    $model = config('indexer.model');
                    $model::updateOrCreate([
                        'url' => $url,
                        'lang' => $this->getLang($doc),
                        'title' => $this->getTitle($doc, $url),
                        'tag' => $node,
                        'content' => $this->cleanup($paragraph->textContent),
                    ]);
                }
            }
            $bar->advance();
        }
        $bar->finish();
    }

    /**
     * Get all urls that are present on a given url for the same host.
     *
     *
     * @see https://laracasts.com/discuss/channels/laravel/spatie-crawler-how-to-list-out-all-the-urls-from-the-base-url-crawl
     *
     * @param string $url
     * @return array
     */
    public function getUrls(string $url): array
    {
        $urls = [];

        if ($url != '') {
            $baseUrl = $url;
            $prefix = 'https';
            if (strpos($baseUrl, 'ttps://') === false) {
                $prefix = 'http';
            }
            $html = $this->loader->load($url);
            //Getting the exact url without http or https
            $url = str_replace('http://www.', '', $url);
            $url = str_replace('https://www.', '', $url);
            $url = str_replace('http://', '', $url);
            $url = str_replace('https://', '', $url);
            //Parsing the url for getting host information
            $parse = parse_url('https://'.$url);
            //Parsing the html of the base url
            $dom = new \DOMDocument();
            @$dom->loadHTML($html);
            // grab all the on the page
            $xpath = new \DOMXPath($dom);
            //finding the a tag
            $hrefs = $xpath->evaluate('/html/body//a');
            //Loop to display all the links
            $length = $hrefs->length;
            //Converting URLs to add the www prefix to host to a common array
            $baseUrl = str_replace('http://'.$parse['host'], 'http://www.'.$parse['host'], $baseUrl);
            $baseUrl = str_replace('https://'.$parse['host'], 'https://www.'.$parse['host'], $baseUrl);
            $urls = [$baseUrl];
            $allUrls = [$baseUrl];
            for ($i = 0; $i < $length; $i++) {
                $href = $hrefs->item($i);
                $url = $href->getAttribute('href');
                $url = str_replace('http://'.$parse['host'], 'http://www.'.$parse['host'], $url);
                $url = str_replace('https://'.$parse['host'], 'https://www.'.$parse['host'], $url);
                //Replacing the / at the end of any url if present
                if (substr($url, -1, 1) == '/') {
                    $url = substr_replace($url, '', -1);
                }
                array_push($allUrls, $url);
            }

            //Looping for filtering the URLs into a distinct array
            foreach ($allUrls as $url) {
                //Limiting the number of urls on the site
                if (count($urls) >= 300) {
                    break;
                }
                //Filter the null links and images
                if (strpos($url, '#') === false) {
                    //Filtering the links with host
                    if (strpos($url, 'https://'.$parse['host']) !== false || strpos($url, 'https://www.'.$parse['host']) !== false) {
                        //Replacing the / at the end of any url if present
                        if (substr($url, -1, 1) == '/') {
                            $url = substr_replace($url, '', -1);
                        }
                        //Checking if the link is already preset in the final array
                        $urlSuffix = str_replace('http://www.', '', $url);
                        $urlSuffix = str_replace('https://www.', '', $urlSuffix);
                        $urlSuffix = str_replace('http://', '', $urlSuffix);
                        $urlSuffix = str_replace('https://', '', $urlSuffix);

                        if ($urlSuffix != $parse['host']) {
                            array_push($urls, $url);
                        }
                    }
                    //Filtering the links without host
                    if (strpos($url, $parse['host']) === false) {
                        if (substr($url, 0, 1) == '/') {
                            //Replacing the / at the end of any url if present
                            if (substr($url, -1, 1) == '/') {
                                $url = substr_replace($url, '', -1);
                            }
                            $newUrl = 'http://www.'.$parse['host'].$url;
                            $secondUrl = 'https://www.'.$parse['host'].$url;
                            if ($url != $parse['host']) {
                                //Checking if the link is already preset in the final array and the common array
                                if (! in_array($secondUrl, $urls) && ! in_array($secondUrl, $allUrls) && ! in_array($newUrl, $allUrls)) {
                                    if ($prefix == 'https') {
                                        $newUrl = $secondUrl;
                                    }
                                    array_push($urls, $newUrl);
                                }
                            }
                        }
                    }
                }
            }
        }

        return array_unique($urls);
    }

    /**
     * Create a complete sitemap for a domain.
     *
     * @param string $next
     * @param array $todo
     * @param array $index
     * @return array
     */
    public function createSitemap(?string $next = null, $todo = [], $index = []): array
    {
        // see if there is a next url, that should be scraped
        if ($next) {
            $this->info($next);

            // get all urls on the page
            $nextUrls = $this->getUrls($next);

            // add all urls, that are not yet present on the index to the todos
            $todo = array_unique(
                array_merge(
                    $todo,
                    array_diff($nextUrls, $index)
                )
            );

            // add the visited url to the index
            $index[] = $next;

            // the next url is the first url from the todos of which it is removed
            $next = array_shift($todo);

            return $this->createSitemap($next, $todo, $index);
        } else {
            return $index;
        }
    }

    public function removeTag(string $tag, string $html): string
    {
        return preg_replace("#<$tag(.*?)>(.*?)</$tag>#is", '', $html);
    }

    public function cleanup(string $string)
    {
        // remove multiple spaces
        $string = preg_replace('/\s+/', ' ', $string);

        // remove line breaks
        $string = str_replace("\n", '', $string);

        // remove spaces at start and end of string
        $string = trim($string);

        // remove colons at start and end of string
        $string = trim($string, ':');

        return $string;
    }

    public function getTitle(DOMDocument $doc, string $url)
    {
        foreach (config('indexer.title') as $tag) {
            foreach ($doc->getElementsByTagName($tag) as $title) {
                return $this->cleanup($title->textContent);
            }
        }

        // if no h1 or title present, try to get meta tags title
        if ($tags = get_meta_tags($url)) {
            if (array_key_exists('title', $tags)) {
                return $this->cleanup($tags['title']);
            }
        }

        return $url;
    }

    public function getLang(DOMDocument $doc)
    {
        $html = $doc->getElementsByTagName('html')->item(0);

        if ($html->hasAttributes()) {
            foreach ($html->attributes as $attr) {
                if ($attr->nodeName == 'lang') {
                    return $attr->nodeValue;
                }
            }
        }

        return null;
    }

    public function getExcludedPaths()
    {
        return collect(config('indexer.exclude'))->filter(function ($url) {
            return str_ends_with($url, '*');
        })->map(function ($url) {
            return trim($url, '*');
        });
    }
}
