<?php

namespace AwStudio\Indexer;

use AwStudio\Indexer\Models\PageIndexRecord;
use InvalidArgumentException;

class Indexer
{
    /**
     *
     * @param string $searchterm
     * @param string|null $language
     * @return array
     * @throws InvalidArgumentException
     */
    public static function search(string $searchterm, string|null $language = null): array
    {
        $query = PageIndexRecord::query();

        if ($language) {
            $query->where('lang', $language);
        }

        $query = $query->whereRaw('LOWER(`content`) LIKE ?', ['%'.strtolower($searchterm).'%'])
            ->orWhereRaw('LOWER(`title`) LIKE ?', ['%'.strtolower($searchterm).'%']);

        
        return $query->get();
    }
}
