<?php
class SearchIndexFixture extends CakeTestFixture {
    var $name = 'SearchIndex';
    var $table = 'search_index';

    var $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
        'model' => array('type' => 'string', 'null' => false, 'default' => NULL, 'key' => 'index', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'foreign_key' => array('type' => 'integer', 'null' => false, 'default' => NULL),
        'data' => array('type' => 'text', 'null' => false, 'default' => NULL, 'key' => 'index', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'slug' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'summary' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'url' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'active' => array('type' => 'boolean', 'null' => false, 'default' => '1', 'key' => 'index'),
        'published' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
        'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
        'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
        'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'model' => array('column' => array('model', 'foreign_key'), 'unique' => 1), 'active' => array('column' => 'active', 'unique' => 0), 'data' => array('column' => 'data', 'unique' => 0,  'type' => 'fulltext')),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
    );

}