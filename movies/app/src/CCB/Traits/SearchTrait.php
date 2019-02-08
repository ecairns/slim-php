<?php

namespace CCB\Traits;

Trait SearchTrait {
    /**
     * Replaces spaces with full text search wildcards
     *
     * @param string $term
     * @return string
     */
    protected function fullTextWildcards($term) {
        // removing symbols used by MySQL
        $reservedSymbols = ['-', '+', '<', '>', '@', '(', ')', '~'];
        $term            = str_replace($reservedSymbols, '', $term);

        $words = explode(' ', $term);

        foreach ($words as $key => $word) {
            /*
             * applying + operator (required word) only big words
             * because smaller ones are not indexed by mysql
             */
            if (strlen($word) >= 3) {
                $words[$key] = '+' . $word . '*';
            }
        }

        $searchTerm = implode(' ', $words);

        return $searchTerm;
    }

    /**
     * Search for films
     *
     * @param array $params
     * @return array
     */
    public function search(array $params) {
        $docs = $this->fetch($params);

        $resultSet = [
            "numFound" => count($docs),
            "docs"     => $docs
        ];

        return $resultSet;
    }
}