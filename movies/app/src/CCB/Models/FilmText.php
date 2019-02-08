<?php

namespace CCB\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Table is used for Fulltext searches
 *
 * Class FilmText
 * @package CCB\Models
 */
class FilmText extends Model {
    protected $primaryKey = "film_id";
    protected $table = 'film_text';
    public $timestamps = false;

    protected $hidden = ['pivot'];
}