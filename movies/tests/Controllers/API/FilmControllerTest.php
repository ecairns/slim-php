<?php

use CCB\Services\FilmService;
use CCB\Controllers\API\FilmController;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;

class FilmControllerTest extends SlimTestCase {
    public function env($uri, array $params, $method = 'GET') {
        $queryString = http_build_query($params);

        return Environment::mock([
                'REQUEST_METHOD' => strtoupper($method),
                'REQUEST_URI'    => $uri,
                'QUERY_STRING'   => $queryString
            ]
        );
    }

    public function getSearchResponse(array $params) {
        $this->service = new FilmService();
        $controller    = new FilmController($this->service);

        $environment = $this->env("/movies", $params, "GET");

        $req = Request::createFromEnvironment($environment);
        $res = new Response();

        $response = $controller->search($req, $res, []);
        $json     = $response->getBody()->__toString();

        return (object)[
            "headers" => $response->getHeaders(),
            "status"  => $response->getStatusCode(),
            "body"    => json_decode($json)
        ];
    }

    public function testTitleSearch() {
        $params = ["title" => "dragon", "order" => "id"];

        $res = $this->getSearchResponse($params);

        $this->assertSame($res->status, 200);
        $this->assertSame($res->body->numFound, 4);
        $this->assertSame(count($res->body->docs), 4);
        $this->assertSame($res->body->docs[0]->film_id, 124);
        $this->assertSame($res->body->docs[0]->title, "CASPER DRAGONFLY");
    }

    public function testTitleRatingSearch() {
        $params = ["title" => "dragon", "rating" => "NC-17", "order" => "id"];

        $res = $this->getSearchResponse($params);

        $this->assertSame($res->status, 200);
        $this->assertSame($res->body->numFound, 2);
        $this->assertSame(count($res->body->docs), 2);
        $this->assertSame($res->body->docs[0]->film_id, 250);
        $this->assertSame($res->body->docs[0]->title, "DRAGON SQUAD");
    }

    public function testTitleRatingCategorySearch() {
        $params = ["title" => "dragon", "rating" => "PG-13", "category" => 16, "order" => "id"];

        $res = $this->getSearchResponse($params);

        $this->assertSame($res->status, 200);
        $this->assertSame($res->body->numFound, 1);
        $this->assertSame(count($res->body->docs), 1);
        $this->assertSame($res->body->docs[0]->film_id, 299);
        $this->assertSame($res->body->docs[0]->title, "FACTORY DRAGON");
    }

    public function testCategorySearch() {
        $params = ["category" => 16, "order" => "id"];

        $res = $this->getSearchResponse($params);

        $this->assertSame($res->status, 200);
        $this->assertSame($res->body->numFound, 57);
        $this->assertSame(count($res->body->docs), 57);
        $this->assertSame($res->body->docs[0]->film_id, 41);
        $this->assertSame($res->body->docs[0]->title, "ARSENIC INDEPENDENCE");
    }
}
