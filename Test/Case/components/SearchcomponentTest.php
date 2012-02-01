<?php
/* Search Test cases generated on: 2011-01-20 02:01:04 : 1295489404*/
App::import('component', 'Searchable.Search');

class SearchcomponentTest extends CakeTestCase {
	function startTest() {
		$this->Search =& new Searchcomponent();
	}

	function endTest() {
		unset($this->Search);
		ClassRegistry::flush();
	}

}