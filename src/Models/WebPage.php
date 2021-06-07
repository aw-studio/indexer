<?php

namespace AwStudio\Indexer\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class WebPage extends Model
{
    use Searchable;

    /**
     * Fillable attributes.
     *
     * @var array
     */
    protected $fillable = [
        'url',
        'lang',
        'title',
        'tag',
        'content',
    ];

    /**
     * Hiddem attributes.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return config('indexer.table');
    }
}
