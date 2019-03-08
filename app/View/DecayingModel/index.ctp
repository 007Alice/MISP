<div class="templates index">
    <h2><?php echo __('Decaying Models');?></h2>
    <div class="pagination">
        <ul>
        <?php
        $this->Paginator->options(array(
            'update' => '.span12',
            'evalScripts' => true,
            'before' => '$(".progress").show()',
            'complete' => '$(".progress").hide()',
        ));

            echo $this->Paginator->prev('&laquo; ' . __('previous'), array('tag' => 'li', 'escape' => false), null, array('tag' => 'li', 'class' => 'prev disabled', 'escape' => false, 'disabledTag' => 'span'));
            echo $this->Paginator->numbers(array('modulus' => 20, 'separator' => '', 'tag' => 'li', 'currentClass' => 'active', 'currentTag' => 'span'));
            echo $this->Paginator->next(__('next') . ' &raquo;', array('tag' => 'li', 'escape' => false), null, array('tag' => 'li', 'class' => 'next disabled', 'escape' => false, 'disabledTag' => 'span'));
        ?>
        </ul>
    </div>
    <table class="table table-striped table-hover table-condensed">
    <tr>
            <th><?php echo $this->Paginator->sort('id');?></th>
            <th><?php echo $this->Paginator->sort('org');?></th>
            <th><?php echo $this->Paginator->sort('name');?></th>
            <th><?php echo $this->Paginator->sort('description');?></th>
            <th><?php echo $this->Paginator->sort('parameters');?></th>
            <?php if ($isAclTemplate): ?>
                <th class="actions"><?php echo __('Actions');?></th>
            <?php endif; ?>
    </tr><?php
foreach ($decayingModel as $item): ?>
    <tr>
        <td class="short"><a href="<?php echo $baseurl."/decayingModel/view/".$item['DecayingModel']['id']; ?>"><?php echo h($item['DecayingModel']['id']); ?>&nbsp;</a></td>
        <td class="short">
            <?php
                echo $this->OrgImg->getOrgImg(array('name' => $item['DecayingModel']['org_id'], 'size' => 24));
            ?>
            &nbsp;
        </td>
        <td><a href="<?php echo $baseurl."/decayingModel/view/".$item['DecayingModel']['id']; ?>"><?php echo h($item['DecayingModel']['name']); ?>&nbsp;</a></td>
        <td><?php echo h($item['DecayingModel']['description']); ?>&nbsp;</td>
        <td data-toggle="json" onclick="document.location.href ='<?php echo $baseurl."/decayingModels/view/".$item['DecayingModel']['id']; ?>'"><?php echo json_encode($item['DecayingModel']['parameters']); ?>&nbsp;</td>
        <?php if ($isAclTemplate): ?>
        <td class="short action-links">
            <?php echo $this->Html->link('', array('action' => 'view', $item['DecayingModel']['id']), array('class' => 'icon-list-alt', 'title' => 'View'));?>
            <?php echo $this->Html->link('', array('action' => 'edit', $item['DecayingModel']['id']), array('class' => 'icon-edit', 'title' => 'Edit'));?>
            <?php echo $this->Form->postLink('', array('action' => 'delete', $item['DecayingModel']['id']), array('class' => 'icon-trash', 'title' => 'Delete'), __('Are you sure you want to delete DecayingModel #' . $item['DecayingModel']['id'] . '?'));?>
        </td>
        <?php endif; ?>
    </tr><?php
endforeach; ?>
    </table>
    <p>
    <?php
    echo $this->Paginator->counter(array(
    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
    ));
    ?>
    </p>
    <div class="pagination">
        <ul>
        <?php
            echo $this->Paginator->prev('&laquo; ' . __('previous'), array('tag' => 'li', 'escape' => false), null, array('tag' => 'li', 'class' => 'prev disabled', 'escape' => false, 'disabledTag' => 'span'));
            echo $this->Paginator->numbers(array('modulus' => 20, 'separator' => '', 'tag' => 'li', 'currentClass' => 'active', 'currentTag' => 'span'));
            echo $this->Paginator->next(__('next') . ' &raquo;', array('tag' => 'li', 'escape' => false), null, array('tag' => 'li', 'class' => 'next disabled', 'escape' => false, 'disabledTag' => 'span'));
        ?>
        </ul>
    </div>

</div>

<script>
$(document).ready(function() {
    $('[data-toggle="json"]').each(function(i) {
        var parsedJson = syntaxHighlightJson($(this).text().trim());
        $(this).html(parsedJson);
    });
});
</script>
<?php
    echo $this->element('/genericElements/SideMenu/side_menu', array('menuList' => 'decayingModel', 'menuItem' => 'index'));
?>
