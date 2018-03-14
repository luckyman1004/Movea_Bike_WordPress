<?php
/*
 * Name: Call To Action
 * Section: content
 * Description: Call to action button
 * 
 */

/* @var $options array */
/* @var $wpdb wpdb */

if (empty($options['text'])) {
    $options['text'] = 'Call to action';
}
if (empty($options['background'])) {
    $options['background'] = '#256F9C';
}
if (empty($options['color'])) {
    $options['color'] = '#ffffff';
}
if (empty($options['url'])) {
    $options['url'] = '#';
}
if (empty($options['font_size'])) {
    $options['font_size'] = '16';
}
if (empty($options['font_family'])) {
    $options['font_family'] = 'Helvetica, Arial, sans-serif';
}
?>
<table border="0" cellpadding="0" cellspacing="0" width="500" class="responsive-table">
    <tr>
        <td align="center" style="text-align: center; padding: 20px;" class="padding-copy">

            <a href="<?php echo $options['url'] ?>" target="_blank" rel="noopener" style="line-height: normal; font-size: <?php echo $options['font_size'] ?>px; font-family: <?php echo $options['font_family'] ?>; font-weight: normal; color: <?php echo $options['color'] ?>; text-decoration: none; background-color: <?php echo $options['background'] ?>; border-top: 15px solid <?php echo $options['background'] ?>; border-bottom: 15px solid <?php echo $options['background'] ?>; border-left: 25px solid <?php echo $options['background'] ?>; border-right: 25px solid <?php echo $options['background'] ?>; border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; display: inline-block;"><?php echo $options['text'] ?></a>

            <div itemscope="" itemtype="http://schema.org/EmailMessage">
                <div itemprop="potentialAction" itemscope="" itemtype="http://schema.org/ViewAction">
                    <meta itemprop="url" content="<?php echo $options['url'] ?>" />
                    <meta itemprop="name" content="<?php echo esc_attr($options['text']) ?>" />
                </div>
                <meta itemprop="description" content="<?php echo esc_attr($options['text']) ?>" />
            </div>
        </td>
    </tr>
</table>


