<?php
echo $this->Form->create('SearchIndex', array(
  'url' => array(
    'plugin' => 'searchable',
    'controller' => 'search_indexes',
    'action' => 'index'
  )
));
echo $this->Form->input('term', array('label' => 'Search', 'id' => 'SearchSearch'));
echo $this->Form->end();
?>
