<?php

namespace CCB\Controllers\API;

use CCB\Interfaces\ModelServiceInterface;
use CCB\Services\FilmService;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * REST API Controller for Movies
 *
 * Class FilmController
 * @package CCB\Controllers\API
 */
class FilmController {
    /**
     * @var FilmService
     */
    protected $service;

    /**
     * @var array Default models to be included with Actors
     */
    protected $defaultWith = ["language", "categories"];

    /**
     * MovieController constructor.
     * @param ModelServiceInterface $service
     */
    public function __construct(ModelServiceInterface $service) {
        $this->service = $service;
    }

    public function read(Request $request, Response $response, array $args) {
        $id        = !empty($args["id"]) ? (int)$args["id"] : null;
        $resultSet = new \stdClass();
        $params    = ["with" => $this->defaultWith];

        if ($id) {
            $resultSet = $this->service->fetchId($id, $params);
        }

        return $response->withJson($resultSet);
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return mixed
     */
    public function actors(Request $request, Response $response, array $args) {
        $id     = !empty($args["id"]) ? (int)$args["id"] : null;
        $actors = [];
        $params = ["with" => ['actors']];

        if ($id) {
            $resultSet = $this->service->fetchId($id, $params);
            $actors    = $resultSet->actors;
        }

        return $response->withJson($actors);
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return Response
     */
    public function search(Request $request, Response $response, array $args) {
        $params = ["with" => $this->defaultWith];

        $q = $request->getQueryParams();

        $params['id']       = !empty($q['id']) ? explode(",", $q['id']) : null;
        $params['category'] = !empty($q['category']) ? explode(",", $q['category']) : null;
        $params['rating']   = !empty($q['rating']) ? explode(",", $q['rating']) : null;
        $params['title']    = !empty($q['title']) ? $q['title'] : null;
        $params['limit']    = !empty($q['limit']) ? $q['limit'] : null;
        $params['offset']   = !empty($q['offset']) ? $q['offset'] : 0;

        if (!empty($q['order'])) {
            switch ($q['order']) {
                case "id":
                    $params['orderBy'] = "film.film_id";
                    break;
            }
        }

        $resultSet = $this->service->search($params);

        return $response->withJson($resultSet);
    }
}

