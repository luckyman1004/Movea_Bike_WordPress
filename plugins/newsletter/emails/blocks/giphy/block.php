<?php
/*
 * Name: Giphy
 * Section: content
 * Description: Add a Giphy image
 * 
 */

/* @var $options array */
/* @var $wpdb wpdb */

$default_options = array(
    'view'=>'View online',
    'text'=>'Few words summary',
    'block_background'=>'#ffffff',
    'font_family'=>$font_family,
    'font_size'=>13,
    'color'=>'#999999'
);

$options = array_merge($default_options, $options);
$options['block_padding_top'] = '15px';
$options['block_padding_bottom'] = '15px';

?>

<table width="100%" border="0" cellpadding="0" align="center" cellspacing="0">
    <tr>
        <td width="100%" valign="top" align="center">
            <img src="<?php echo $options['giphy_url'] ?>" />
        </td>
    </tr>
</table>

