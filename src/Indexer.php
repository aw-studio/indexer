<?php

namespace AwStudio\Indexer;

use AwStudio\Indexer\Models\PageIndexRecord;

class Indexer
{
    public static function search(string $searchterm)
    {
        return PageIndexRecord::whereRaw('LOWER(`content`) LIKE ?', ['%'.strtolower($searchterm).'%'])
            ->orWhereRaw('LOWER(`title`) LIKE ?', ['%'.strtolower($searchterm).'%'])
            ->get();
    }
}
