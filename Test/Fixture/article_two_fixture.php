<?php
/**
 * ArticleTwo Fixture
 *
 * @package searchable
 * @subpackage searchable.tests.fixtures
 */
class ArticleTwoFixture extends CakeTestFixture {

/**
 * Name property
 *
 * @var string 'AnotherArticle'
 */
	public $name = 'ArticleTwo';

/**
 * fields property
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'key' => 'primary'),
		'title' => array('type' => 'string', 'null' => false),
		'body' => array('type' => 'text', 'null' => false),
		'slug' => array('type' => 'string', 'null' => false),
		'published' => array('type' => 'boolean', 'null' => false),
		'category_id' => array('type' => 'integer', 'null' => false),
		'created' => 'datetime',
		'updated' => 'datetime');

/**
 * records property
 *
 * @var array
 */
	public $records = array(
		array('id' => 1, 'title' => 'First Article', 'body' => 'First Article', 'slug' => 'first_article', 'published' => true, 'category_id' => 1, 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'),
		array('id' => 2, 'title' => 'Second Article', 'body' => 'Second Article', 'slug' => 'second_article', 'published' => true, 'category_id' => 1, 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'),
		array('id' => 3, 'title' => 'Third Article', 'body' => 'Third Article', 'slug' => 'third_article', 'published' => true, 'category_id' => 3, 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'));
}