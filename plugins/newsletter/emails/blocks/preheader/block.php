<?php
/*
 * Name: Preheader
 * Section: header
 * Description: Preheader
 * 
 */

/* @var $options array */
/* @var $wpdb wpdb */



if (empty($options['view'])) {
    $options['view'] = 'View online';
}
if (empty($options['text'])) {
    $options['text'] = 'Few words summary';
}
?>


<table border="0" cellpadding="0" cellspacing="0" width="750" class="responsive-table" style="max-width: 100%!important">
    <tr>
        <td style="padding: 20px; text-align: center; font-size: 13px!important; color: #444;" width="50%" valign="top" align="center">
            <?php echo $options['text']?>
        </td>
        <td style="padding: 20px; text-align: center;" width="50%" valign="top" align="center">
            <a href="{email_url}" target="_blank" rel="noopener" style="text-decoration: none; color: #444; font-size: 13px!important;"><?php echo $options['view']?></a>
        </td>
    </tr>
</table>
