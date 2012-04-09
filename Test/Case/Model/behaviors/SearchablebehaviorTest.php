<?php
App::import('Core', 'Model');
App::import('Behavior', 'Searchable.Searchable');
/**
 * Article model
 *
 * @package search
 * @subpackage searchable.tests.cases.models.behaviors
 */
class Article extends CakeTestModel {

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

class SearchablebehaviorTest extends CakeTestCase {

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
    }

    function endTest() {
        unset($this->Article);
        ClassRegistry::flush();
    }

    function testInsert() {
        $article = $this->Article->findById(1);

        $this->Article->create();
        $this->Article->save($article);
        $result = $this->SearchIndex->find('all');
        $this->assertEqual($result, array());

        $this->Article->Behaviors->attach('Searchable.Searchable');
        $this->Article->create();
        $this->Article->save($article);
        $result = $this->SearchIndex->find('all');
        $this->assertNotNull($result);
        $this->assertEqual($result['0']['SearchIndex']['data'], '{"8f2bc0d4fec30a58981b5594054de3ffbd92078d":"First Article","bbf72108d87cf36a9d1112add1ef00031082688a":"First Article","a35eb6d57a708e3ae98439e62894de478cf744e6":"first_article"}');

        $result = $this->SearchIndex->find('count');
        $this->assertEqual(1, $result);

        $articleTwo = $this->Article->findById(2);
        $this->Article->create();
        $this->Article->save($articleTwo);
        $result = $this->SearchIndex->find('all');
        $this->assertNotNull($result);
        $this->assertEqual($result['0']['SearchIndex']['data'], '{"8f2bc0d4fec30a58981b5594054de3ffbd92078d":"First Article","bbf72108d87cf36a9d1112add1ef00031082688a":"First Article","a35eb6d57a708e3ae98439e62894de478cf744e6":"first_article"}');
        $this->assertEqual($result['1']['SearchIndex']['data'], '{"8f2bc0d4fec30a58981b5594054de3ffbd92078d":"Second Article","bbf72108d87cf36a9d1112add1ef00031082688a":"Second Article","a35eb6d57a708e3ae98439e62894de478cf744e6":"second_article"}');

        $result = $this->SearchIndex->find('count');
        $this->assertEqual(2, $result);
    }

    function testUpdate() {
        $this->Article->Behaviors->attach('Searchable.Searchable');

        $article = $this->Article->findById(1);
        $articleTwo = $this->Article->findById(2);
        $articleThree = $this->Article->findById(3);

        $this->Article->save($article);
        $this->Article->save($articleTwo);

        $result = $this->SearchIndex->find('all');
        $this->assertNotNull($result);
        $this->assertEqual($result['0']['SearchIndex']['data'], '{"8f2bc0d4fec30a58981b5594054de3ffbd92078d":"First Article","bbf72108d87cf36a9d1112add1ef00031082688a":"First Article","a35eb6d57a708e3ae98439e62894de478cf744e6":"first_article"}');
        $this->assertEqual($result['1']['SearchIndex']['data'], '{"8f2bc0d4fec30a58981b5594054de3ffbd92078d":"Second Article","bbf72108d87cf36a9d1112add1ef00031082688a":"Second Article","a35eb6d57a708e3ae98439e62894de478cf744e6":"second_article"}');

        $result = $this->SearchIndex->find('count');
        $this->assertEqual(2, $result);

        $this->Article->create();
        $this->Article->save($articleTwo);

        $result = $this->SearchIndex->find('all');
        $this->assertNotNull($result);
        $this->assertEqual($result['0']['SearchIndex']['data'], '{"8f2bc0d4fec30a58981b5594054de3ffbd92078d":"First Article","bbf72108d87cf36a9d1112add1ef00031082688a":"First Article","a35eb6d57a708e3ae98439e62894de478cf744e6":"first_article"}');
        $this->assertEqual($result['1']['SearchIndex']['data'], '{"8f2bc0d4fec30a58981b5594054de3ffbd92078d":"Second Article","bbf72108d87cf36a9d1112add1ef00031082688a":"Second Article","a35eb6d57a708e3ae98439e62894de478cf744e6":"second_article"}');

        $result = $this->SearchIndex->find('count');
        $this->assertEqual(2, $result);

        $articleThree['Article']['id'] = 2;
        $this->Article->create();
        $this->Article->save($articleThree);

        $result = $this->SearchIndex->find('all');
        $this->assertNotNull($result);
        $this->assertEqual($result['0']['SearchIndex']['data'], '{"8f2bc0d4fec30a58981b5594054de3ffbd92078d":"First Article","bbf72108d87cf36a9d1112add1ef00031082688a":"First Article","a35eb6d57a708e3ae98439e62894de478cf744e6":"first_article"}');
        $this->assertEqual($result['1']['SearchIndex']['data'], '{"8f2bc0d4fec30a58981b5594054de3ffbd92078d":"Third Article","bbf72108d87cf36a9d1112add1ef00031082688a":"Third Article","a35eb6d57a708e3ae98439e62894de478cf744e6":"third_article"}');

        $result = $this->SearchIndex->find('count');
        $this->assertEqual(2, $result);

        $articleThree['Article']['id'] = 3;
        $this->Article->create();
        $this->Article->save($articleThree);

        $result = $this->SearchIndex->find('all');
        $this->assertNotNull($result);
        $this->assertEqual($result['0']['SearchIndex']['data'], '{"8f2bc0d4fec30a58981b5594054de3ffbd92078d":"First Article","bbf72108d87cf36a9d1112add1ef00031082688a":"First Article","a35eb6d57a708e3ae98439e62894de478cf744e6":"first_article"}');
        $this->assertEqual($result['1']['SearchIndex']['data'], '{"8f2bc0d4fec30a58981b5594054de3ffbd92078d":"Third Article","bbf72108d87cf36a9d1112add1ef00031082688a":"Third Article","a35eb6d57a708e3ae98439e62894de478cf744e6":"third_article"}');
        $this->assertEqual($result['2']['SearchIndex']['data'], '{"8f2bc0d4fec30a58981b5594054de3ffbd92078d":"Third Article","bbf72108d87cf36a9d1112add1ef00031082688a":"Third Article","a35eb6d57a708e3ae98439e62894de478cf744e6":"third_article"}');

        $result = $this->SearchIndex->find('count');
        $this->assertEqual(3, $result);

        $articleTwo['Article']['id'] = 2;
        $this->Article->create();
        $this->Article->save($articleTwo);

        $result = $this->SearchIndex->find('all');
        $this->assertNotNull($result);
        $this->assertEqual($result['0']['SearchIndex']['data'], '{"8f2bc0d4fec30a58981b5594054de3ffbd92078d":"First Article","bbf72108d87cf36a9d1112add1ef00031082688a":"First Article","a35eb6d57a708e3ae98439e62894de478cf744e6":"first_article"}');
        $this->assertEqual($result['1']['SearchIndex']['data'], '{"8f2bc0d4fec30a58981b5594054de3ffbd92078d":"Second Article","bbf72108d87cf36a9d1112add1ef00031082688a":"Second Article","a35eb6d57a708e3ae98439e62894de478cf744e6":"second_article"}');
        $this->assertEqual($result['2']['SearchIndex']['data'], '{"8f2bc0d4fec30a58981b5594054de3ffbd92078d":"Third Article","bbf72108d87cf36a9d1112add1ef00031082688a":"Third Article","a35eb6d57a708e3ae98439e62894de478cf744e6":"third_article"}');

        $result = $this->SearchIndex->find('count');
        $this->assertEqual(3, $result);
    }

    function testDelete() {
        $this->Article->Behaviors->attach('Searchable.Searchable');

        $article = $this->Article->findById(1);
        $articleTwo = $this->Article->findById(2);
        $articleThree = $this->Article->findById(3);

        $this->Article->save($article);
        $this->Article->save($articleTwo);
        $this->Article->save($articleThree);

        $result = $this->SearchIndex->find('count');
        $this->assertEqual(3, $result);

        $this->Article->delete(2);

        $result = $this->SearchIndex->find('all');
        $this->assertNotNull($result);
        $this->assertEqual($result['0']['SearchIndex']['data'], '{"8f2bc0d4fec30a58981b5594054de3ffbd92078d":"First Article","bbf72108d87cf36a9d1112add1ef00031082688a":"First Article","a35eb6d57a708e3ae98439e62894de478cf744e6":"first_article"}');
        $this->assertEqual($result['1']['SearchIndex']['data'], '{"8f2bc0d4fec30a58981b5594054de3ffbd92078d":"Third Article","bbf72108d87cf36a9d1112add1ef00031082688a":"Third Article","a35eb6d57a708e3ae98439e62894de478cf744e6":"third_article"}');
        
        $result = $this->SearchIndex->find('count');
        $this->assertEqual(2, $result);

        $this->Article->delete(1);
        $this->Article->delete(3);
        $result = $this->SearchIndex->find('count');
        $this->assertEqual(0, $result);
    }

    function testPublished() {
        $this->Article->Behaviors->attach('Searchable.Searchable', array(
            'published' => 'created'
        ));

        $result = $this->SearchIndex->find('count');
        $this->assertEqual(0, $result);

        $article = $this->Article->findById(1);
        $articleTwo = $this->Article->findById(2);
        $articleThree = $this->Article->findById(3);
        $this->Article->save($article);
        $this->Article->save($articleTwo);
        $this->Article->save($articleThree);

        $result = $this->SearchIndex->find('count');
        $this->assertEqual(3, $result);

        $articleTwo['Article']['id'] = 4;
        $articleTwo['Article']['published'] = false;
        $this->Article->save($articleTwo);
        $result = $this->SearchIndex->find('count');
        $this->assertEqual(4, $result);

        $result = $this->SearchIndex->field('published', array(
            'SearchIndex.foreign_key' => 4,
            'SearchIndex.model' => 'Article'
        ));
        $this->assertEqual('2007-03-18 10:41:23', $result);
    }

    function testScope() {
        $this->Article->Behaviors->attach('Searchable.Searchable', array(
            'scope' => array('published' => true)
        ));

        $result = $this->SearchIndex->find('count');
        $this->assertEqual(0, $result);

        $article = $this->Article->findById(1);
        $articleTwo = $this->Article->findById(2);
        $articleThree = $this->Article->findById(3);
        $this->Article->save($article);
        $this->Article->save($articleTwo);
        $this->Article->save($articleThree);

        $result = $this->SearchIndex->find('count');
        $this->assertEqual(3, $result);

        $articleTwo['Article']['id'] = 4;
        $articleTwo['Article']['published'] = false;
        $this->Article->save($articleTwo);
        $result = $this->SearchIndex->find('count');
        $this->assertEqual(4, $result);

        $result = $this->SearchIndex->field('active', array(
            'foreign_key' => 4,
            'model' => 'Article'
        ));
        $this->assertFalse($result);

        $articleTwo['Article']['published'] = true;
        $this->Article->save($articleTwo);
        $result = $this->SearchIndex->find('count');
        $this->assertEqual(4, $result);
        $result = $this->SearchIndex->field('active', array(
            'foreign_key' => 4,
            'model' => 'Article'
        ));
        $this->assertTrue($result);

        $this->Article->Behaviors->detach('Searchable.Searchable');
        $this->Article->Behaviors->attach('Searchable.Searchable', array(
            'scope' => array('published' => true, 'created >' => date('Y-m-d H:i:s'))
        ));
        $result = $this->SearchIndex->find('count', array(
            'conditions' => array('active' => 1)
        ));
        $this->assertEqual(4, $result);

        $this->Article->save($article);
        $articleTwo['Article']['id'] = 2;
        $this->Article->save($articleTwo);
        $this->Article->save($articleThree);
        $articleTwo['Article']['id'] = 4;
        $this->Article->save($articleTwo);

        $result = $this->SearchIndex->find('count', array(
            'conditions' => array('active' => 1)
        ));
        $this->assertEqual(0, $result);
    }

    function testName() {
        $article = $this->Article->findById(1);

        $this->Article->Behaviors->attach('Searchable.Searchable', array(
            'name' => 'id'
        ));
        $this->Article->save($article);
        $result = $this->SearchIndex->field('name', array(
            'foreign_key' => 1,
            'model' => 'Article'
        ));
        $this->assertEqual(1, $result);

        $this->Article->Behaviors->detach('Searchable.Searchable');
        $this->Article->Behaviors->attach('Searchable.Searchable', array(
            'name' => false
        ));
        $this->Article->save($article);
        $result = $this->SearchIndex->field('name', array(
            'foreign_key' => 1,
            'model' => 'Article'
        ));
        $this->assertNull($result);

        $this->Article->Behaviors->detach('Searchable.Searchable');
        $this->Article->Behaviors->attach('Searchable.Searchable');
        $article['Article']['title'] = null;
        $this->Article->save($article);
        $result = $this->SearchIndex->field('name', array(
            'foreign_key' => 1,
            'model' => 'Article'
        ));
        $this->assertNull($result);

        $article['Article']['title'] = 'First Article';
        $this->Article->save($article);
        $result = $this->SearchIndex->field('name', array(
            'foreign_key' => 1,
            'model' => 'Article'
        ));
        $this->assertEqual('First Article', $result);

        $article['Article']['title'] = null;
        $this->Article->save($article);
        $result = $this->SearchIndex->field('name', array(
            'foreign_key' => 1,
            'model' => 'Article'
        ));
        $this->assertEqual('First Article', $result);
    }

    function testFields() {
        $article = $this->Article->findById(1);

        $this->Article->Behaviors->attach('Searchable.Searchable', array(
            'fields' => 'title'
        ));
        $this->Article->save($article);
        $result = $this->SearchIndex->field('data', array(
            'foreign_key' => 1,
            'model' => 'Article'
        ));
        $this->assertEqual('{"8f2bc0d4fec30a58981b5594054de3ffbd92078d":"First Article"}', $result);

        $this->Article->Behaviors->detach('Searchable.Searchable');
        $this->Article->Behaviors->attach('Searchable.Searchable', array(
            'fields' => array('Article' => 'title')
        ));
        $this->Article->save($article);
        $result = $this->SearchIndex->field('data', array(
            'foreign_key' => 1,
            'model' => 'Article'
        ));
        $this->assertEqual('{"8f2bc0d4fec30a58981b5594054de3ffbd92078d":"First Article"}', $result);


        $this->Article->Behaviors->detach('Searchable.Searchable');
        $this->Article->Behaviors->attach('Searchable.Searchable', array(
            'fields' => array('Article.title')
        ));
        $this->Article->save($article);
        $result = $this->SearchIndex->field('data', array(
            'foreign_key' => 1,
            'model' => 'Article'
        ));
        $this->assertEqual('{"8f2bc0d4fec30a58981b5594054de3ffbd92078d":"First Article"}', $result);

        $result = $this->SearchIndex->find('count', array(
            'conditions' => array('active' => 1)
        ));
        $this->assertEqual(1, $result);
    }

    function testGetSearchableData() {
        $this->Article->Behaviors->attach('Searchable.Searchable');
        $this->ArticleTwo->Behaviors->attach('Searchable.Searchable');

        $article = $this->Article->findById(1);
        $articleTwo = $this->ArticleTwo->findById(1);
        $this->Article->save($article);
        $this->ArticleTwo->save($articleTwo);

        $result = $this->SearchIndex->field('data', array(
            'foreign_key' => 1,
            'model' => 'Article'
        ));
        $this->assertEqual('{"8f2bc0d4fec30a58981b5594054de3ffbd92078d":"First Article","bbf72108d87cf36a9d1112add1ef00031082688a":"First Article","a35eb6d57a708e3ae98439e62894de478cf744e6":"first_article"}', $result);
        $result = $this->SearchIndex->field('data', array(
            'foreign_key' => 1,
            'model' => 'ArticleTwo'
        ));
        $this->assertEqual('[]', $result);

        $this->ArticleTwo->searchable = true;
        $this->ArticleTwo->save($articleTwo);
        $result = $this->SearchIndex->field('data', array(
            'foreign_key' => 1,
            'model' => 'ArticleTwo'
        ));
        $this->assertEqual('{"5e403b654f08c66ea5a0f9fd04d2c49ef21963d3":"Post Title","0d3faf2f0a2e36bdcf532a4321cf703d59e0709e":"Post Body","d1196d2b1eeb5cff825619e836b18507d1df3cb3":"First Category"}', $result);
    }

    function testCleanValue() {
        $this->ArticleTwo->Behaviors->attach('Searchable.Searchable');

        $articleTwo = $this->ArticleTwo->findById(1);
        $this->ArticleTwo->save($articleTwo);

        $result = $this->SearchIndex->field('name', array(
            'foreign_key' => 1,
            'model' => 'ArticleTwo'
        ));
        $this->assertEqual('<clean>First Article</clean>', $result);
    }


    function testCustomurl() {
        $this->Article->Behaviors->attach('Searchable.Searchable', array(
            'url' => array(
                'Package' => array(2 => 'name'),
                'Maintainer' => array('slug' => 'username')
            ),
        ));
        $this->ArticleTwo->Behaviors->attach('Searchable.Searchable', array(
            'url' => array(
                'Package' => array(4 => 'name'),
                'Maintainer' => array(0 => 'username')
            ),
            'allowNumericKeys' => true,
        ));

        $article = $this->Article->findById(1);
        $articleTwo = $this->ArticleTwo->findById(1);
        $this->Article->save($article);
        $this->ArticleTwo->save($articleTwo);

        $result = $this->SearchIndex->field('url', array(
            'foreign_key' => 1,
            'model' => 'Article'
        ));
        $this->assertEqual('{"plugin":null,"controller":"articles","action":"view","0":"name","slug":"username"}', $result);
        $result = $this->SearchIndex->field('url', array(
            'foreign_key' => 1,
            'model' => 'ArticleTwo'
        ));
        $this->assertEqual('{"plugin":null,"controller":"article_twos","action":"view","4":"name","0":"username"}', $result);
    }

    function testContain() {
        $this->Article->Behaviors->attach('Searchable.Searchable', array(
            'fields' => array(
                'category_id' => 'Category.title',
            ),
        ));
        $article = $this->Article->findById(1);

        $this->Article->save($article);
        $result = $this->SearchIndex->field('data', array(
            'foreign_key' => 1,
            'model' => 'Article'
        ));
        $this->assertEqual('{"e49b09a6dbc60db73f5f12292459abf164452300":"First Category"}', $result);

        $article['Category']['title'] = 'Second Category';
        $this->Article->save($article);
        $result = $this->SearchIndex->field('data', array(
            'foreign_key' => 1,
            'model' => 'Article'
        ));
        $this->assertEqual('{"e49b09a6dbc60db73f5f12292459abf164452300":"Second Category"}', $result);

        $this->ArticleTwo->Behaviors->attach('Searchable.Searchable', array(
            'fields' => array(
                'category_id' => 'Category.title',
            ),
            'extra' => 'slug'
        ));
        $article = $this->ArticleTwo->findById(1);

        $this->ArticleTwo->save($article);
        $result = $this->SearchIndex->field('data', array(
            'foreign_key' => 1,
            'model' => 'ArticleTwo'
        ));
        $this->assertEqual('[]', $result);

        $article['Category']['title'] = 'Second Category';
        $this->ArticleTwo->searchable = true;
        $this->ArticleTwo->save($article);
        $result = $this->SearchIndex->field('data', array(
            'foreign_key' => 1,
            'model' => 'ArticleTwo'
        ));
        $this->assertEqual('{"5e403b654f08c66ea5a0f9fd04d2c49ef21963d3":"Post Title","0d3faf2f0a2e36bdcf532a4321cf703d59e0709e":"Post Body","d1196d2b1eeb5cff825619e836b18507d1df3cb3":"First Category"}', $result);

        $result = $this->SearchIndex->field('slug', array(
            'foreign_key' => 1,
            'model' => 'ArticleTwo'
        ));
        $this->assertEqual('<clean>first_article</clean>', $result);
    }

}
