<?php

namespace CCB\Interfaces;

/**
 * Interface ModelServiceInterface
 * @package CCB\Interfaces
 */
interface ModelServiceInterface {
    /**
     * @param array $params
     * @return mixed
     */
    public function search(array $params);

    /**
     * @param       $id
     * @param array $params
     * @return mixed
     */
    public function fetchId($id, array $params = []);

    /**
     * @param array $ids
     * @param array $params
     * @return mixed
     */
    public function fetchIds(array $ids, array $params = []);

    /**
     * @param array $params
     * @return mixed
     */
    public function fetch(array $params = []);
}