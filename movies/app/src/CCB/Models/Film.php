<?php

namespace CCB\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Film
 * @package CCB\Models
 */
class Film extends Model {
    protected $primaryKey = "film_id";
    protected $table = 'film';
    public $timestamps = false;

    protected $hidden = ['pivot'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function actors() {
        return $this->belongsToMany(Actor::class, "film_actor", "film_id", "actor_id");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories() {
        return $this->belongsToMany(Category::class, "film_category", "film_id", "category_id");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function language() {
        return $this->hasOne(Language::class, "language_id");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function text() {
        return $this->hasOne(FilmText::class, "film_id");
    }
}