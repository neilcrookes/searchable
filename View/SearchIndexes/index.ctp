<h2>Search results</h2>
<?php
echo $this->Form->create('SearchIndex', array('url' => array('plugin' => 'searchable', 'controller' => 'search_indexes', 'action' => 'index')));
echo $this->Form->input('term', array('label' => 'Search'));
echo $this->Form->input('type', array('empty' => 'All',));
echo $this->Form->end('View Search Results');
?>
<?php if (!empty($results)): ?>
    <ul>
        <?php foreach ($results as $result) : ?>
        <li>
            <h3><?php echo $this->Html->link($result['SearchIndex']['name'], json_decode($result['SearchIndex']['url'], true)); ?></h3>
            <?php if (!empty($result['SearchIndex']['summary'])): ?>
                <p><?php echo $result['SearchIndex']['summary']; ?></p>
            <?php else : ?>
                <?php echo $this->Searchable->snippets($result['SearchIndex']['data']); ?>
            <?php endif; ?>
        </li>
        <?php endforeach; ?>
    </ul>
    <?php
        $params = array_intersect_key($this->params['named'], array_flip(array('type', 'term')));
        $params = array_map('urlencode', $params);
        $this->Paginator->options(array('url' => $params));
    ?>
    <div class="paging">
        <?php echo $this->Paginator->prev('<< '.__('previous'), array(), null, array('class'=>'disabled')); ?>
     | 	<?php echo $this->Paginator->numbers(); ?>
        <?php echo $this->Paginator->next(__('next').' >>', array(), null, array('class'=>'disabled')); ?>
    </div>
<?php else: ?>
    <p>Sorry, your search did not return any matches.</p>
<?php endif; ?>
