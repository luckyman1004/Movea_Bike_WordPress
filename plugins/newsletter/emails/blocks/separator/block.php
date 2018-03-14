<?php
/*
 * Name: Separator
 * Section: content
 * Description: Separator
 * 
 */

/* @var $options array */
/* @var $wpdb wpdb */



if (empty($options['color'])) {
    $options['color'] = '#dddddd';
}
if (empty($options['height'])) {
    $options['height'] = 1;
}
?>


<table border="0" cellpadding="0" cellspacing="0" width="750" class="responsive-table" style="max-width: 100%!important">
    <tr>
        <td style="padding: 20px;" bgcolor="<?php echo $options['background'] ?>">
            <div style="height: <?php echo $options['height'] ?>px!important; background-color: <?php echo $options['color'] ?>; border: 0; margin:0; padding: 0; line-height: 0; width: 100%!important; display: block;"></div>
        </td>
    </tr>
</table>
