<?php
echo $form->create('SearchIndex', array(
  'url' => array(
    'plugin' => 'searchable',
    'controller' => 'search_indexes',
    'action' => 'index'
  )
));
echo $form->input('term', array('label' => 'Search', 'id' => 'SearchSearch'));
echo $form->end();
?>
