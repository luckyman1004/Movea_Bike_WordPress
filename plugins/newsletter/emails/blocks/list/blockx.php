<?php
/*
 * Name: List
 * Section: content
 * Description: A well designed list for your strength points
 * 
 */

/* @var $options array */
/* @var $wpdb wpdb */

$defaults = array(
    'bullet' => '1',
    'text_1' => 'Element 1',
    'text_2' => 'Element 2',
    'text_3' => 'Element 3',
    'font_size' => '16',
    'font_family' => 'Helvetica, Arial, sans-serif',
    'color' => '#000000',
    'background' => '#ffffff',
);

$options = array_merge($defaults, $options);

?>
<table border="0" cellpadding="0" align="center" cellspacing="0" width="100%" style="width: 100%!important; max-width: <?php echo $width ?>px!important">
    <tr>
        <td style="padding: 15px 20px;" bgcolor="<?php echo $options['background'] ?>">

            <table cellspacing="0" cellpadding="5" align="left">
                <?php
                for ($i = 1; $i <= 10; $i++) {
                    if (empty($options['text_' . $i])) {
                        continue;
                    }
                    ?>
                    <tr>
                        <td style="font-size: <?php echo $options['font_size'] ?>px; font-family: <?php echo $options['font_family'] ?>; color: <?php echo $options['color'] ?>;">
                            <span style="color: <?php echo $options['bullet_color'] ?>">&#x<?php echo $options['bullet'] ?>;</span>&nbsp;<?php echo $options['text_' . $i] ?>
                        </td>
                    </tr>
                    <?php
                    echo '</td></tr>';
                }
                ?>
            </table>

        </td>
    </tr>
</table>

