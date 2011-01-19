<?php
class SearchIndex extends SearchableAppModel {
    var $name = 'SearchIndex';
    var $useTable = 'search_index';
    var $_findMethods = array(
        'types' => true
    );

/**
 * Returns array of types (models) used in the Search Index with model name as
 * the key and the humanised form as the value.
 *
 * @return array
 */
    function getTypes() {
        if (($types = Cache::read('search_index_types')) !== false) {
            return $types;
        }
         return $this->find('types');
     }

    function _findTypes($state, $query, $results = array()) {
        if ($state == 'before') {
            $query['fields'] = array(
                'DISTINCT(SearchIndex.model)',
                'DISTINCT(SearchIndex.model)'
            );
            return $query;
        } else if ($state == 'after') {
            $types = array();
            $results = Set::extract('/SearchIndex/model', $results);

            foreach ($results as $type) {
                $types[$type] = Inflector::humanize(Inflector::tableize($type));
            }

            if (!empty($types)) {
                Cache::write('search_index_types', $types);
            }
            return $types;
        }
    }

}