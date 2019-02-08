<?php

namespace CCB\Services;

use CCB\Interfaces\ModelServiceInterface;
use CCB\Models\Film;
use CCB\Traits\SearchTrait;

/**
 * Class FilmService
 * @package CCB\Services
 */
class FilmService implements ModelServiceInterface {
    use SearchTrait;

    protected $config;

    /**
     * MovieSearchService constructor.
     * @param array $config
     */
    public function __construct(array $config = []) {
        $this->config = $config;
    }

    /**
     * Fetch single record with primary id
     *
     * @param int   $id
     * @param array $params
     * @return mixed
     */
    public function fetchId($id, array $params = []) {
        return reset($this->fetchIds([$id], $params));
    }

    /**
     * Fetch records with primary ids
     *
     * @param array $ids
     * @param array $params
     * @return Film[]|\Illuminate\Database\Eloquent\Collection
     */
    public function fetchIds(array $ids, array $params = []) {
        $params['id'] = $ids;
        return $this->fetch($params);
    }

    /**
     * Fetch records
     *
     * @param array $params
     * @return Film[]|\Illuminate\Database\Eloquent\Collection
     */
    public function fetch(array $params = []) {
        $query   = (new Film)->newQuery();
        $orderBy = !empty($params['orderBy']) ? $params['orderBy'] : null;

        if (!empty($params['id'])) {
            $query = $query->whereIn("film_id", $params['id']);
        }

        if (!empty($params['rating'])) {
            $query = $query->whereIn("rating", $params['rating']);
        }

        if (!empty($params['category'])) {
            $query = $query->join("film_category", function ($join) use ($params) {
                $join->on('film_category.film_id', 'film.film_id')
                    ->whereIn('film_category.category_id', $params['category']);
            });
        }

        if (!empty($params['title'])) {
            $searchable = ['film_text.title', 'film_text.description'];

            $columns = implode(',', $searchable);

            $searchableTerm = $this->fullTextWildcards($params['title']);

            $sql = "MATCH ({$columns}) AGAINST (? IN BOOLEAN MODE)";

            $query = $query->join("film_text", "film.film_id", "=", "film_text.film_id")
                ->selectRaw("film.*, $sql as score", [$searchableTerm])
                ->whereRaw("MATCH ({$columns}) AGAINST (? IN BOOLEAN MODE)", $searchableTerm);

            $orderBy = empty($orderBy) ? 'score DESC' : $orderBy;
        }

        if (!empty($params['with'])) {
            $query = $query->with($params['with']);
        }

        if (!empty($orderBy)) {
            $query = $query->orderByRaw($orderBy);
        }

        return $query->get();
    }
}