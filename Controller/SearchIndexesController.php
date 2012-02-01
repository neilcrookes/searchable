<?php
class SearchIndexesController extends SearchableAppController {

  var $name = 'SearchIndexes';
  var $paginate = array('SearchIndex' => array('limit' => 10));
  var $helpers = array('Searchable.Searchable');

  function index() {

    // Redirect with search data in the URL in pretty format
    if (!empty($this->data)) {
      $redirect = array();
      if (isset($this->data['SearchIndex']['term'])
      && !empty($this->data['SearchIndex']['term'])) {
        $redirect['term'] = urlencode(urlencode($this->data['SearchIndex']['term']));
      } else {
        $redirect['term'] = 'null';
      }
      if (isset($this->data['SearchIndex']['type'])
      && !empty($this->data['SearchIndex']['type'])) {
        $redirect['type'] = $this->data['SearchIndex']['type'];
      } else {
        $redirect['type'] = 'All';
      }
      $this->redirect($redirect);
    }

    // Add default scope condition
    $this->paginate['SearchIndex']['conditions'] = array('SearchIndex.active' => 1);

    // Add published condition NULL or < NOW()
    $this->paginate['SearchIndex']['conditions']['OR'] = array(
      array('SearchIndex.published' => null),
      array('SearchIndex.published <= ' => date('Y-m-d H:i:s'))
    );

    // Add type condition if not All
    if (isset($this->request->params['named']['type'])
    && $this->request->params['named']['type'] != 'All') {
      $this->data['SearchIndex']['type'] = $this->request->params['named']['type'];
      $this->paginate['SearchIndex']['conditions']['model'] = $this->request->params['named']['type'];
    }

    // Add term condition, and sorting
    if (isset($this->request->params['named']['term'])
    && $this->request->params['named']['term'] != 'null') {
      $this->request->data['SearchIndex']['term'] = $this->request->params['named']['term'];
      $term = $this->request->params['named']['term'];
      App::import('Core', 'Sanitize');
      $term = Sanitize::escape($term);
      $this->paginate['SearchIndex']['conditions'][] = "MATCH(data) AGAINST('$term' IN BOOLEAN MODE)";
      $this->paginate['SearchIndex']['fields'] = "*, MATCH(data) AGAINST('$term' IN BOOLEAN MODE) AS score";
      $this->paginate['SearchIndex']['order'] = "score DESC";
    }

    $results = $this->paginate();

    // Get types for select drop down
    $types = $this->SearchIndex->getTypes();
    $this->set(compact('results', 'types'));
    $this->pageTitle = 'Search';

  }

}
?>