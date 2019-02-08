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
     * MovieController constructor.
     * @param ModelServiceInterface $service
     */
    public function __construct(ModelServiceInterface $service) {
        $this->service = $service;
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return Response
     */
    public function search(Request $request, Response $response, array $args) {
        $params = ["with" => ["language", "categories", "actors"]];

        $q = $request->getQueryParams();

        $params['id']       = !empty($q['id']) ? explode(",", $q['id']) : null;
        $params['category'] = !empty($q['category']) ? explode(",", $q['category']) : null;
        $params['rating']   = !empty($q['rating']) ? explode(",", $q['rating']) : null;
        $params['title']    = !empty($q['title']) ? $q['title'] : null;

        $resultSet = $this->service->search($params);

        return $response->withJson($resultSet);
    }
}

