<?php
/**
 * Searchable helper library.
 *
 * Allows manipulation of search data
 *
 * @package       searchable
 * @subpackage    searchable.view.helpers
 */
class SearchableHelper extends AppHelper {

/**
 * Included helpers.
 *
 * @var array
 */
    var $helpers = array('Text');

/**
 * Contains fallback record data
 *
 * @var string
 */
    var $recordData = null;

/**
 * Highlights the term within the search_index data
 *
 * ### Options:
 * 
 * - `field` Field used in view->data[SearchIndex] which contains the search term
 * - `maxLength` Max length of highlighted text
 * - `radius` The amount of characters that will be returned on each side of the founded phrase
 * - `ending` Ending that will be appended
 * - `highlight` Array of options for TextHelper::highlight()
 *
 * @param Object $data Object containing data
 * @param array $options An array of options
 * @return string highlighted search snippet
 */
    function snippets($data, $options = array()) {
        $options = array_merge(array(
            'field' => 'term',
            'maxLength' => 255,
            'radius' => 20,
            'ending' => '',
            'highlight' => array(),
        ), $options);

        if (isset($this->data['SearchIndex'][$options['field']])) {
            $term = trim($this->data['SearchIndex'][$options['field']]);
        } else {
            $term = null;
        }

        $data = json_decode($data, true);
        $snippets = '';

        while (strlen($snippets) < $options['maxLength'] && $value = next($data)) {
            $snippets .= ' ' . $this->Text->highlight(
                $this->Text->excerpt($value, $term, $options['radius'], $options['ending']),
                $term,
                $options['highlight']
            );
        }
        return trim($snippets);
    }

/**
 * Returns unhashed data
 *
 * @param string $field Name of key in $data to be returned
 * @param mixed $data Object containing value to be returned or null
 * @return void
 * @author Jose Diaz-Gonzalez
 */
    function data($field, $data = null) {
        $field = sha1($field);

        if (empty($data) && !empty($this->recordData)) {
            $data = $this->recordData;
        }

        if (!is_object($data)) {
            return false;
        }

        if (isset($data->{$field})) {
            return $data->{$field};
        }
        return false;
    }

/**
 * Sets a record for use with Searchable::data()
 *
 * @param Object $data Object containing data
 * @return void
 * @author Jose Diaz-Gonzalez
 **/
    function setRecord($data = null) {
        $this->recordData = $data;
    }

}