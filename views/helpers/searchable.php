<?php
class SearchableHelper extends AppHelper {
    var $helpers = array('Html', 'Text');

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

}