<?php

namespace CCB\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Category
 * @package CCB\Models
 */
class Category extends Model {
    protected $primaryKey = "category_id";
    protected $table = 'category';
    public $timestamps = false;

    protected $hidden = ['pivot'];
}