<?php

namespace AwStudio\Indexer\Models;

use Illuminate\Database\Eloquent\Model;

class PageIndexRecord extends Model
{
    /**
     * Page Index Database Table.
     *
     * @var array
     */
    protected $table = 'page_index';

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
        'content'
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
}
