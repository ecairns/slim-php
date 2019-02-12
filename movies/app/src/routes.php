<?php

$app->get('/movies',  "MovieApiController:search");
$app->get('/movies/{id}',  "MovieApiController:read");
$app->get('/movies/{id}/actors',  "MovieApiController:actors");
