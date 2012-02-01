<?php
App::import('Core', 'Model');
App::import('Model', 'Category');
App::import('Behavior', 'Searchable.Searchable');
App::import('Behavior', 'Searchable.Queryable');
/**
 * Article model
 *
 * @package search
 * @subpackage searchable.tests.cases.models.behaviors
 */
class Article extends CakeTestModel {
	var $belongsTo = array('Category');
}

/**
 * ArticleTwo model
 *
 * @package search
 * @subpackage searchable.tests.cases.models.behaviors
 */
class ArticleTwo extends Article {

/**
 * Trigger for getSearchableData
 *
 * @var array
 */
    var $searchable = false;

    function getSearchableData($data = array()) {
        if ($this->searchable) {
            return array(
                'Post.title' => 'Post Title',
                'Post.body' => 'Post Body',
                'Category.name' => 'First Category',
            );
        }
        return array();
    }

    function cleanValue($value) {
        return '<clean>' . $value . '</clean>';
    }

}

class QueryablebehaviorTest extends CakeTestCase {

/**
 * Fixtures used in the SessionTest
 *
 * @var array
 */
    var $fixtures = array(
        'plugin.searchable.article',
        'plugin.searchable.article_two',
        'plugin.searchable.category',
        'plugin.searchable.search_index',
    ); 

    function startTest() {
        $this->Article = ClassRegistry::init('Article');
        $this->ArticleTwo = ClassRegistry::init('ArticleTwo');
        $this->SearchIndex = ClassRegistry::init('Searchable.SearchIndex');

		$articles = $this->Article->find('all');
		$this->Article->Behaviors->attach('Searchable.Searchable');
		$this->Article->saveAll($articles);
		$this->Article->Behaviors->detach('Searchable.Searchable');

		$articles = $this->ArticleTwo->find('all');
		$this->ArticleTwo->Behaviors->attach('Searchable.Searchable');
		$this->ArticleTwo->saveAll($articles);
		$this->ArticleTwo->Behaviors->detach('Searchable.Searchable');
    }

    function endTest() {
        unset($this->Article, $this->ArticleTwo, $this->SearchIndex);
        ClassRegistry::flush();
    }

	function testSettings() {
		$this->Article->Behaviors->attach('Searchable.Queryable');
		$expected = array(
			'searchModel' => 'Searchable.SearchIndex',
			'searchField' => 'data',
			'foreignKey' => 'foreign_key',
			'modelIdentifier' => 'model',
			'includeIndex' => false,
			'boolean' => true,
			'minScore' => 2.0,
			'scoreField' => 'score'
		);
		$this->assertEqual($expected, $this->Article->getSearchSetting());

		$expected = 'Searchable.SearchIndex';
		$this->assertEqual($expected, $this->Article->getSearchSetting('searchModel'));

		$this->Article->setSearchSetting('includeIndex', true);
		$this->assertTrue($this->Article->getSearchSetting('includeIndex'));

		$this->assertNull($this->Article->getSearchSetting('foo'));

		$this->Article->setSearchSetting('foo', 'bar');
		$this->assertNull($this->Article->getSearchSetting('foo'));
	}

	function testBoolean() {
		$this->Article->Behaviors->attach('Searchable.Queryable');
		$expected = array(
			0 => array(
				'Article' => array(
					'id' => 3,
					'title' => 'Third Article',
					'body' => 'Third Article',
					'slug' => 'third_article',
					'published' => 1,
					'category_id' => 3,
					'created' => '2007-03-18 10:43:23',
					'updated' => '2007-03-18 10:45:31'
				)
			)
		);
		$result = $this->Article->find('all', array('term' => 'Third'));
		$this->assertEqual($expected, $result);

		$expected = array(
			0 => array(
				'Article' => array(
					'id' => 1,
					'title' => 'First Article',
					'body' => 'First Article',
					'slug' => 'first_article',
					'published' => 1,
					'category_id' => 1,
					'created' => '2007-03-18 10:39:23',
					'updated' => '2007-03-18 10:41:31'
				)
			)
		);
		$result = $this->Article->find('all', array('term' => '+First Second'));
		$this->assertEqual($expected, $result);

		$result = $this->Article->find('all', array(
			'term' => 'First Second Third -third_article -second_article'
		));
		$this->assertEqual($expected, $result);

		$expected = array(
			0 => array(
				'Article' => array(
					'id' => 1,
					'title' => 'First Article'
				)
			)
		);
		$result = $this->Article->find('all', array(
			'term' => 'First Second Third -third_article -second_article',
			'fields' => array('Article.id', 'Article.title')
		));
		$this->assertEqual($expected, $result);

		$expected = array(
			0 => array(
				'Article' => array(
					'id' => 1,
					'title' => 'First Article'
				),
				'SearchIndex' => array(
					'id' => 1
				)
			)
		);
		$result = $this->Article->find('all', array(
			'term' => 'First Second Third -third_article -second_article',
			'fields' => array('Article.id', 'Article.title', 'SearchIndex.id')
		));
		$this->assertEqual($expected, $result);

		$expected = array(
			0 => array(
				'Article' => array(
					'id' => 1,
					'title' => 'First Article'
				),
				'SearchIndex' => array(
                    'id' => 1,
                    'model' => 'Article',
                    'foreign_key' => 1,
                    'data' => '{"8f2bc0d4fec30a58981b5594054de3ffbd92078d":"First Article","bbf72108d87cf36a9d1112add1ef00031082688a":"First Article","a35eb6d57a708e3ae98439e62894de478cf744e6":"first_article"}',
                    'name' => 'First Article',
                    'slug' => null,
                    'summary' => null,
                    'url' => '{"plugin":null,"controller":"articles","action":"view","0":1}',
                    'active' => 1,
                    'published' => null
				)
			)
		);

		$this->Article->setSearchSetting('includeIndex', true);
		$result = $this->Article->find('all', array(
			'term' => 'First Second Third -third_article -second_article',
			'fields' => array('Article.id', 'Article.title')
		));
		unset($result[0]['SearchIndex']['created'], $result[0]['SearchIndex']['modified']);
		$this->assertEqual($expected, $result);
	}

	function testRecursive() {
		$this->Article->Behaviors->attach('Searchable.Queryable');
		$this->Article->recursive = -1;
		$this->Article->find('all', array('term' => '+First Second'));
		$this->assertEqual(-1, $this->Article->recursive);
	}

	function testNonBoolean() {
		$this->Article->Behaviors->attach('Searchable.Queryable');
		$this->Article->setSearchSetting('boolean', false);


		$expected = array(
			0 => array(
				'Article' => array(
					'id' => 1,
					'title' => 'First Article',
					'body' => 'First Article',
					'slug' => 'first_article',
					'published' => 1,
					'category_id' => 1,
					'created' => '2007-03-18 10:39:23',
					'updated' => '2007-03-18 10:41:31',
					'score' => '2.07069373130798'
				)
			),
			1 => array(
				'Article' => array(
					'id' => 3,
					'title' => 'Third Article',
					'body' => 'Third Article',
					'slug' => 'third_article',
					'published' => 1,
					'category_id' => 3,
					'created' => '2007-03-18 10:43:23',
					'updated' => '2007-03-18 10:45:31',
					'score' => '2.07069373130798'
				)
			)
		);

		$result = $this->Article->find('all', array(
			'term' => 'First Third'
		));
		$this->assertEqual($expected, $result);

		$this->Article->setSearchSetting('scoreField', 'foo');
		$result = $this->Article->find('all', array(
			'term' => 'First Third'
		));

		$expected = array(
			0 => array(
				'Article' => array(
					'id' => 1,
					'title' => 'First Article',
					'body' => 'First Article',
					'slug' => 'first_article',
					'published' => 1,
					'category_id' => 1,
					'created' => '2007-03-18 10:39:23',
					'updated' => '2007-03-18 10:41:31',
					'foo' => '2.07069373130798'
				)
			),
			1 => array(
				'Article' => array(
					'id' => 3,
					'title' => 'Third Article',
					'body' => 'Third Article',
					'slug' => 'third_article',
					'published' => 1,
					'category_id' => 3,
					'created' => '2007-03-18 10:43:23',
					'updated' => '2007-03-18 10:45:31',
					'foo' => '2.07069373130798'
				)
			)
		);
		$this->assertEqual($expected, $result);

		$expected = array();
		$this->Article->setSearchSetting('minScore', 4);
		$result = $this->Article->find('all', array(
			'term' => 'First Third'
		));
		$this->assertEqual($expected, $result);
	}

	function testContainableBoolean() {
		$this->Article->Behaviors->attach('Searchable.Queryable');
		$this->Article->Behaviors->attach('Containable');

		$expected = array(
			0 => array(
				'Article' => array(
					'id' => 1,
					'title' => 'First Article'
				),
				'Category' => array(
					'id' => 1,
					'title' => 'First Category'
				)
			)
		);
		$result = $this->Article->find('all', array(
			'term' => '+First',
			'fields' => array('Article.id', 'Article.title'),
			'contain' => array(
				'Category' => array(
					'fields' => array('Category.id', 'Category.title')
				)
			)
		));
		$this->assertEqual($expected, $result);

		$this->Article->contain(array('Category' => array(
			'fields' => array('Category.id', 'Category.title')
		)));
		$result = $this->Article->find('all', array(
			'term' => '+First',
			'fields' => array('Article.id', 'Article.title')
		));
		$this->assertEqual($expected, $result);

		$expected = array(
			0 => array(
				'Article' => array(
					'id' => 2,
					'title' => 'Second Article'
				),
				'Category' => array(
					'id' => 1,
					'title' => 'First Category'
				)
			),
			1 => array(
				'Article' => array(
					'id' => 3,
					'title' => 'Third Article'
				),
				'Category' => array(
					'id' => 3,
					'title' => 'Third Category'
				)
			)
		);
		$result = $this->Article->find('all', array(
			'term' => 'Second Third',
			'fields' => array('Article.id', 'Article.title'),
			'contain' => array(
				'Category' => array(
					'fields' => array('Category.id', 'Category.title')
				),
				'SearchIndex'
			)
		));
		$this->assertEqual($expected, $result);

		$expected = array(
			0 => array(
				'Article' => array(
					'id' => 2,
					'title' => 'Second Article'
				),
				'SearchIndex' => array(
					'id' => 2,
					'foreign_key' => 2,
					'data' => '{"8f2bc0d4fec30a58981b5594054de3ffbd92078d":"Second Article","bbf72108d87cf36a9d1112add1ef00031082688a":"Second Article","a35eb6d57a708e3ae98439e62894de478cf744e6":"second_article"}',
					'model' => 'Article'
				),
				'Category' => array(
					'id' => 1,
					'title' => 'First Category'
				)
			)
		);

		$result = $this->Article->find('all', array(
			'term' => 'Second',
			'fields' => array('Article.id', 'Article.title'),
			'contain' => array(
				'Category' => array(
					'fields' => array('Category.id', 'Category.title')
				),
				'SearchIndex' => array(
					'fields' => array('SearchIndex.id')
				)
			)
		));
		$this->assertEqual($expected, $result);

		$this->Article->contain(array(
			'Category' => array(
				'fields' => array('Category.id', 'Category.title')
			),
			'SearchIndex' => array('fields' => array())
		));
		$result = $this->Article->find('all', array(
			'term' => 'Second',
			'fields' => array('Article.id', 'Article.title')
		));
		$this->assertEqual($expected, $result);
	}

	function testContainableNonBoolean() {
		$this->Article->Behaviors->attach('Searchable.Queryable');
		$this->Article->Behaviors->attach('Containable');
		$this->Article->setSearchSetting('boolean', false);

		$expected = array(
			0 => array(
				'Article' => array(
					'id' => 1,
					'title' => 'First Article',
					'score' => '2.07069373130798'
				),
				'Category' => array(
					'id' => 1,
					'title' => 'First Category'
				)
			)
		);
		$result = $this->Article->find('all', array(
			'term' => '+First',
			'fields' => array('Article.id', 'Article.title'),
			'contain' => array(
				'Category' => array(
					'fields' => array('Category.id', 'Category.title')
				)
			)
		));
		$this->assertEqual($expected, $result);

		$this->Article->contain(array('Category' => array(
			'fields' => array('Category.id', 'Category.title')
		)));
		$result = $this->Article->find('all', array(
			'term' => '+First',
			'fields' => array('Article.id', 'Article.title')
		));
		$this->assertEqual($expected, $result);

		$expected = array(
			0 => array(
				'Article' => array(
					'id' => 2,
					'title' => 'Second Article',
					'score' => '2.07069373130798'
				),
				'Category' => array(
					'id' => 1,
					'title' => 'First Category'
				)
			),
			1 => array(
				'Article' => array(
					'id' => 3,
					'title' => 'Third Article',
					'score' => '2.07069373130798'
				),
				'Category' => array(
					'id' => 3,
					'title' => 'Third Category'
				)
			)
		);
		$result = $this->Article->find('all', array(
			'term' => '+Second ~Third',
			'fields' => array('Article.id', 'Article.title'),
			'contain' => array(
				'Category' => array(
					'fields' => array('Category.id', 'Category.title')
				),
				'SearchIndex'
			)
		));
		$this->assertEqual($expected, $result);

		$expected = array(
			0 => array(
				'Article' => array(
					'id' => 2,
					'title' => 'Second Article',
					'score' => '2.07069373130798'
				),
				'SearchIndex' => array(
					'id' => 2,
					'foreign_key' => 2,
					'data' => '{"8f2bc0d4fec30a58981b5594054de3ffbd92078d":"Second Article","bbf72108d87cf36a9d1112add1ef00031082688a":"Second Article","a35eb6d57a708e3ae98439e62894de478cf744e6":"second_article"}',
					'model' => 'Article'
				),
				'Category' => array(
					'id' => 1,
					'title' => 'First Category'
				)
			)
		);

		$result = $this->Article->find('all', array(
			'term' => 'Second',
			'fields' => array('Article.id', 'Article.title'),
			'contain' => array(
				'Category' => array(
					'fields' => array('Category.id', 'Category.title')
				),
				'SearchIndex' => array(
					'fields' => array('SearchIndex.id')
				)
			)
		));
		$this->assertEqual($expected, $result);

		$this->Article->contain(array(
			'Category' => array(
				'fields' => array('Category.id', 'Category.title')
			),
			'SearchIndex' => array('fields' => array())
		));
		$result = $this->Article->find('all', array(
			'term' => 'Second',
			'fields' => array('Article.id', 'Article.title')
		));
		$this->assertEqual($expected, $result);
	}
}
