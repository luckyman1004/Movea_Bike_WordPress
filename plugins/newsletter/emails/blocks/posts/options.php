<?php
/*
 * @var $options array contains all the options the current block we're ediging contains
 * @var $controls NewsletterControls 
 */
?>

<table class="form-table">
    <tr>
        <th><?php _e('Title', 'newsletter') ?></th>
        <td>
            <?php $controls->text('title'); ?>
        </td>
    </tr>
    <tr>
        <th><?php _e('Read more label', 'newsletter') ?></th>
        <td>
            <?php $controls->text('read_more'); ?>
        </td>
    </tr>
    <tr>
        <th><?php _e('Max', 'newsletter') ?></th>
        <td>
            <?php $controls->select_number('max', 1, 20); ?>
        </td>
    </tr>
    <tr>
        <th><?php _e('Categories', 'newsletter') ?></th>
        <td>
            <?php $controls->categories_group('categories'); ?>
        </td>
    </tr>
    <tr>
        <th><?php _e('Tags', 'newsletter') ?></th>
        <td>
            <?php $controls->text('tags'); ?>
        </td>
    </tr>
    <tr>
        <th><?php _e('View', 'newsletter') ?></th>
        <td>
            <?php $controls->text('view', 70) ?>
        </td>
    </tr>
    <tr>
        <th><?php _e('Layout', 'newsletter') ?></th>
        <td>
            <?php $controls->select('layout', array('one' => 'One column', 'two' => 'Two columns')) ?>
        </td>
    </tr>
    <tr>
        <th><?php _e('Block background', 'newsletter') ?></th>
        <td>
            <?php $controls->color('block_background') ?>
        </td>
    </tr>
</table>
