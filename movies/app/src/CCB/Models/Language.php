<?php

namespace CCB\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Language
 * @package CCB\Models
 */
class Language extends Model {
    protected $primaryKey = "language_id";
    protected $table = 'language';
    public $timestamps = false;

    protected $hidden = ['pivot', 'last_update'];
}