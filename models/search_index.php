<?php
class SearchIndex extends SearchableAppModel {
    var $name = 'SearchIndex';
    var $useTable = 'search_index';
    var $_findMethods = array(
        'search' => true, 'types' => true
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

    function _findSearch($state, $query, $results = array()) {
        if ($state == 'before') {
            $query['conditions'] = array(
                array('SearchIndex.active' => 1),
                'or' => array(
                    array('SearchIndex.published' => null),
                    array('SearchIndex.published <= ' => date('Y-m-d H:i:s'))
                )
            );
            
            if (!empty($query['type'])) {
                $query['conditions']['model'] = $query['type'];
            }

            $term = implode(' ', array_map(array($this, 'replace'), preg_split('/[\s_]/', $query['term']))) . '*';
            if (!empty($query['like'])) {
                $query['conditions'][] = array('or' => array(
                        "MATCH(data) AGAINST('$term')",
                        'SearchIndex.data LIKE' => "%{$query['term']}%"
                ));
            } else {
                $query['conditions'][] = "MATCH(data) AGAINST('{$query['term']}' IN BOOLEAN MODE)";
            }
            
            $query['fields'] = "SearchIndex.foreign_key as id, SearchIndex.name, SearchIndex.summary, MATCH(data) AGAINST('{$query['term']}' IN BOOLEAN MODE) AS score";
            if (empty($query['order'])) {
                $query['order'] = "score DESC";
            }
            return $query;
        } else if ($state == 'after') {
            foreach ($results as &$result) {
                $result = $result['SearchIndex'];
            }
            return $results;
        }
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

    function replace($v) {
        return str_replace(array(' +-', ' +~', ' ++', ' +'), array('-', '~', '+', '+'), " +{$v}");
    }

}