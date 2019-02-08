<?php

namespace CCB\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Actor
 * @package CCB\Models
 */
class Actor extends Model {
    protected $primaryKey = "actor_id";
    protected $table = 'actor';
    public $timestamps = false;

    protected $hidden = ['pivot'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function films() {
        return $this->belongsToMany(Film::class, "film_actor", "actor_id", "film_id");
    }
}