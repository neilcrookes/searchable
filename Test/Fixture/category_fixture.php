<?php
/**
 * Category Fixture
 *
 * @package searchable
 * @subpackage searchable.tests.fixtures
 */
class CategoryFixture extends CakeTestFixture {

/**
 * Name property
 *
 * @var string 'AnotherPost'
 */
	public $name = 'Category';

/**
 * fields property
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'key' => 'primary'),
		'title' => array('type' => 'string', 'null' => false),
		'slug' => array('type' => 'string', 'null' => false),
		'created' => 'datetime',
		'updated' => 'datetime');

/**
 * records property
 *
 * @var array
 */
	public $records = array(
		array('id' => 1, 'title' => 'First Category', 'slug' => 'first_post', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'),
		array('id' => 2, 'title' => 'Second Category', 'slug' => 'second_post', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'),
		array('id' => 3, 'title' => 'Third Category', 'slug' => 'third_post', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'));
}