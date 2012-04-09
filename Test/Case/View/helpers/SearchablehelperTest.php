<?php
App::import('Helper', array('Searchable.Searchable', 'Text'));

/**
 * TagHelperTest class
 *
 * @package       app
 * @subpackage    app.tests.cases.views.helpers
 */
class SearchablehelperTest extends CakeTestCase {

/**
 * Variable containing example data
 *
 * @var string
 */
    var $data = null;

/**
 * setUp method
 *
 * @access public
 * @return void
 */
    function startTest() {
        $this->Searchable =& new Searchablehelper();
        $this->Searchable->Text =& new TextHelper();
        $this->data = json_decode('{
            "29aa6958f92fc57f42276ea1036b47eca77703ab": "239",
            "217c17b9f806f8d0348d78443af4ffecf10b3722": "170",
            "ed4b26478c7819c6871e7bfda4cd30501db97ef5": "SwiftMail-component"
        }');
    }

/**
 * tearDown method
 *
 * @access public
 * @return void
 */
    function endTest() {
        unset($this->Searchable);
        ClassRegistry::flush();
    }

    function testSnippets() {
        $actual = $this->Searchable->snippets(json_encode($this->data));
        $this->assertEqual('170 SwiftMail-component', $actual);

        $this->Searchable->data['SearchIndex']['term'] = 'swift';
        $actual = $this->Searchable->snippets(json_encode($this->data));
        $this->assertEqual('170 <span class="highlight">Swift</span>Mail-component', $actual);
    }

    function testData() {
        $actual = $this->Searchable->data('Package.id', $this->data);
        $this->assertEqual('239', $actual);

        $this->Searchable->setRecord('record');
        $actual = $this->Searchable->data('Package.id');
        $this->assertFalse($actual);

        $this->Searchable->setRecord($this->data);
        $actual = $this->Searchable->data('Package.id');
        $this->assertEqual('239', $actual);

        $this->Searchable->setRecord('record');
        $actual = $this->Searchable->data('Package.id', $this->data);
        $this->assertEqual('239', $actual);

        $actual = $this->Searchable->data('ModelName.fieldName', $this->data);
        $this->assertFalse($actual);
    }

    function testSetRecord() {
        $this->assertNull($this->Searchable->recordData);

        $this->Searchable->setRecord('record');
        $this->assertNotNull($this->Searchable->recordData);

        $this->Searchable->setRecord();
        $this->assertNull($this->Searchable->recordData);
    }

}
?>