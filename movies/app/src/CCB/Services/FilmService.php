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
        $rows =  $this->fetchIds([$id], $params);
        return $rows->first();
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
        $MAX_LIMIT = 100;  // maybe define in a config somewhere,
        $DEFAULT_LIMIT = 10;

        $query  = (new Film)->newQuery();
        $offset = !empty($params['offset']) ? (int)$params['offset'] : 0;
        $limit  = !empty($params['limit']) ? (int)$params['limit'] : $DEFAULT_LIMIT;

        if ($offset < 0) $offset = 0;
        if (empty($limit) || $limit > $MAX_LIMIT) $limit = $MAX_LIMIT;

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

        if (!empty($params['COUNT'])) {
            //Using a search like solr this is unnecessary, in mysql, I've read running 2 queries is best for getting count
            $query = $query->selectRaw('count(1) as c');
            $row   = $query->get()->first();

            return $row->c;
        }
        else if ($limit) {
            $query = $query->offset($offset)->limit($limit);
        }

        return $query->get();
    }
}