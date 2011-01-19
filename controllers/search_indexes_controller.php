<?php
class SearchIndexesController extends SearchableAppController {
    var $name = 'SearchIndexes';
    var $paginate = array('SearchIndex' => array('limit' => 10));
    var $helpers = array('Searchable');

    function index($term = null) {
        // Redirect with search data in the URL in pretty format
        if (!empty($this->data)) {
            $redirect = array(
                'plugin' => $this->params['plugin'],
                'controller' => $this->params['controller'],
                'action' => $this->params['index'],
                'type' => 'All',
            );
            if (isset($this->data['SearchIndex']['type']) && !empty($this->data['SearchIndex']['type'])) {
                $redirect['type'] = $this->data['SearchIndex']['type'];
            } elseif (isset($this->params['type']) && $this->params['type'] != 'All') {
                $redirect['type'] = $this->params['type'];
            } else {
                $redirect['type'] = 'All';
            }

            if (isset($this->data['SearchIndex']['term']) && !empty($this->data['SearchIndex']['term'])) {
                $redirect['term'] = $this->data['SearchIndex']['term'];
            } elseif (isset($this->params['term']) && !empty($this->params['term'])) {
                $redirect['term'] = $this->params['term'];
            } else {
                $redirect['term'] = null;
            }
            $this->redirect($redirect);
        }

        // Add default scope condition
        // Add published condition NULL or < NOW()
        $this->paginate = array('SearchIndex' => array(
                array('SearchIndex.active' => 1),
                'or' => array(
                    array('SearchIndex.published' => null),
                    array('SearchIndex.published <= ' => date('Y-m-d H:i:s'))
                )
        ));

        // Add type condition if not All
        if (isset($this->params['type']) && $this->params['type'] != 'All') {
            $this->data['SearchIndex']['type'] = Sanitize::escape($this->params['type']);
            $this->paginate['SearchIndex']['conditions']['model'] = $this->data['SearchIndex']['type'];
        }

        // Add term condition, and sorting
        if (!$term && isset($this->params['term'])) {
            $term = $this->params['term'];
        }
        if ($term) {
            App::import('Core', 'Sanitize');
            $term = Sanitize::escape($term);
            $this->data['SearchIndex']['term'] = $term;
            $this->paginate['SearchIndex']['conditions'][] = "MATCH(data) AGAINST('$term' IN BOOLEAN MODE)";
            $this->paginate['SearchIndex']['fields'] = "*, MATCH(data) AGAINST('$term' IN BOOLEAN MODE) AS score";
            $this->paginate['SearchIndex']['order'] = "score DESC";
        }

        $results = $this->paginate();

        // Get types for select drop down
        $types = $this->SearchIndex->getTypes();
        $this->set(compact('results', 'term', 'types'));
        $this->pageTitle = 'Search';
    }

}