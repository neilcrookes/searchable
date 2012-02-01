<?php
App::import('Core', 'Sanitize');
class SearchComponent extends Component {

/**
 * Reference to current controller
 *
 * @var string
 */
    var $_controller = null;

/**
 * Append Like Query to pagination?
 *
 * @var string
 */
    var $like = false;

    function initialize(&$controller, $settings = array()) {
        $this->_controller = $controller;
        $this->_set($settings);
    }

    function redirectUnlessGet() {
        if (!empty($this->_controller->data)) {
            $redirect = array(
                'plugin' => $this->_controller->params['plugin'],
                'controller' => $this->_controller->params['controller'],
                'action' => $this->_controller->params['action'],
                'type' => 'All',
            );
            if (!empty($this->_controller->data['SearchIndex']['type'])) {
                $redirect['type'] = $this->_controller->data['SearchIndex']['type'];
            } elseif (isset($this->_controller->params['type']) && $this->_controller->params['type'] != 'All') {
                $redirect['type'] = $this->_controller->params['type'];
            } else {
                $redirect['type'] = 'All';
            }

            if (!empty($this->_controller->data['SearchIndex']['term'])) {
                $redirect['term'] = $this->_controller->data['SearchIndex']['term'];
            } elseif (!empty($this->_controller->params['term'])) {
                $redirect['term'] = $this->_controller->params['term'];
            } else {
                $redirect['term'] = null;
            }
            $this->_controller->redirect($redirect);
        }
    }

    function paginate($term = null, $paginateOptions = array()) {
        $this->_controller->paginate = array('SearchIndex' => array_merge_recursive(array(
            'conditions' => array(
                array('SearchIndex.active' => 1),
                'or' => array(
                    array('SearchIndex.published' => null),
                    array('SearchIndex.published <= ' => date('Y-m-d H:i:s'))
                )
            )
        ), $paginateOptions));

        if (isset($this->_controller->request->params['named']['type']) && $this->_controller->request->params['named']['type'] != 'All') {
            $this->_controller->request->data['SearchIndex']['type'] = Sanitize::escape($this->_controller->request->params['named']['type']);
            $this->_controller->paginate['SearchIndex']['conditions']['model'] = $this->_controller->data['SearchIndex']['type'];
        }

        // Add term condition, and sorting
        if (!$term && isset($this->_controller->request->params['named']['term'])) {
            $term = $this->_controller->request->params['named']['term'];
        }

        if ($term) {
            $term = Sanitize::escape($term);
            $this->_controller->request->data['SearchIndex']['term'] = $term;

            $term = implode(' ', array_map(array($this, 'replace'), preg_split('/[\s_]/', $term))) . '*';

            if ($this->like) {
                $this->_controller->paginate['SearchIndex']['conditions'][] = array('or' => array(
                        "MATCH(data) AGAINST('$term')",
                        'SearchIndex.data LIKE' => "%{$this->_controller->data['SearchIndex']['term']}%"
                ));
            } else {
                $this->_controller->paginate['SearchIndex']['conditions'][] = "MATCH(data) AGAINST('$term' IN BOOLEAN MODE)";
            }
            $this->_controller->paginate['SearchIndex']['fields'] = "*, MATCH(data) AGAINST('$term' IN BOOLEAN MODE) AS score";
            if (empty($this->_controller->paginate['SearchIndex']['order'])) {
                $this->_controller->paginate['SearchIndex']['order'] = "score DESC";
            }
        }

        return $this->_controller->paginate('SearchIndex');
    }

    function replace($v) {
        return str_replace(array(' +-', ' +~', ' ++', ' +'), array('-', '~', '+', '+'), " +{$v}");
    }

}