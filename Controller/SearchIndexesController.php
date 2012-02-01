<?php
class SearchIndexesController extends SearchableAppController {
    var $name = 'SearchIndexes';
    var $paginate = array('SearchIndex' => array('limit' => 10));
    var $components = array('Searchable.Search');
    var $helpers = array('Searchable.Searchable');

    function index($term = null) {
    	
		//debug($this->request);
		
        // Redirect with search data in the URL in pretty format
        $this->Search->redirectUnlessGet();

        // Get Pagination results
        $results = $this->Search->paginate($this->request['params']['named']['term']);

        // Get types for select drop down
        $types = $this->SearchIndex->getTypes();

        $this->set(compact('results', 'term', 'types'));
        $this->pageTitle = 'Search';
    }

}